<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        $data = array_map(fn(User $u) => [
            'id' => $u->getId(),
            'identifiant' => $u->getIdentifiant(),
            'nom' => $u->getNom(),
            'prenom' => $u->getPrenom(),
            'mailPro' => $u->getMailPro(),
            'mailPerso' => $u->getMailPerso(),
        ], $users);

        return $this->json($data);
    }

    public function show(User $user): JsonResponse
    {
        return $this->json([
            'id' => $user->getId(),
            'identifiant' => $user->getIdentifiant(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'mailPro' => $user->getMailPro(),
            'mailPerso' => $user->getMailPerso(),
        ]);
    }

    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setIdentifiant($data['identifiant']);
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setMailPro($data['mailPro'] ?? null);
        $user->setMailPerso($data['mailPerso'] ?? null);

        // ⚠️ Hash le mot de passe si nécessaire
        $user->setMotDePasse(password_hash($data['motDePasse'], PASSWORD_BCRYPT));

        $em->persist($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur créé !'], 201);
    }

    public function update(User $user, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nom'])) $user->setNom($data['nom']);
        if (isset($data['prenom'])) $user->setPrenom($data['prenom']);
        if (isset($data['mailPro'])) $user->setMailPro($data['mailPro']);
        if (isset($data['mailPerso'])) $user->setMailPerso($data['mailPerso']);
        if (isset($data['motDePasse'])) {
            $user->setMotDePasse(password_hash($data['motDePasse'], PASSWORD_BCRYPT));
        }

        $em->flush();

        return $this->json(['message' => 'Utilisateur mis à jour !']);
    }

    public function delete(User $user, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur supprimé !']);
    }
}
