<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[Route('/api', name: 'api_')]
class AuthController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        ValidatorInterface $validator,
        JWTTokenManagerInterface $jwt
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json(['error' => 'Requête JSON invalide.'], 400);
        }
        $required = ['identifiant', 'nom', 'prenom', 'motDePasse'];
        $missing = array_diff($required, array_keys($data));
        if ($missing) {
            return $this->json([
                'error' => 'Champs manquants : ' . implode(', ', $missing)
            ], 400);
        }
        $user = new User();
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($user, $method) && $key !== 'motDePasse') {
                $user->$method($value);
            }
        }
        $user->setMotDePasse(
            $hasher->hashPassword($user, $data['motDePasse'])
        );
        $user->setRoles(['ROLE_USER']);

        $lastNumero = $em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->select('MAX(u.numero)')
            ->getQuery()
            ->getSingleScalarResult();

        $nextNumero = $lastNumero ? $lastNumero + 1 : 1001;
        $user->setNumero($nextNumero);

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $em->persist($user);
        $em->flush();
        $token = $jwt->create($user);
        return $this->json([
            'message' => 'Utilisateur créé avec succès',
            'token'   => $token
        ], 201);
    }

}
