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

        $user = new User();
        $user->setIdentifiant($data['identifiant'] ?? '')
             ->setNom($data['nom'] ?? '')
             ->setPrenom($data['prenom'] ?? '')
             ->setMotDePasse($hasher->hashPassword($user, $data['motDePasse'] ?? ''))
             ->setRoles(['ROLE_USER']);

        // ✅ Validation des contraintes
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $em->persist($user);
        $em->flush();

        // ✅ Retourner un token directement
        $token = $jwt->create($user);

        return $this->json([
            'message' => 'Utilisateur créé avec succès',
            'token'   => $token
        ], 201);
    }
}
