-- Désactivation des clés étrangères pour éviter les erreurs d'ordre d'insertion
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Données pour la table `users`
INSERT INTO `users` (`id`, `username`, `nom`, `prenom`, `formation`, `profile_picture`, `email`, `password`, `bio`, `created_at`) VALUES
(1, 'marc_it', 'Dufour', 'Marc', 'Bachelor Informatique - 1ère année', NULL, 'marc@ynov.com', 'hash123', '19 ans. En 1ère année d\'informatique. Je recherche du tutorat via du peer-learning pour mieux gérer la charge de travail technique.', '2026-03-30 13:50:39'),
(2, 'sonia_design', 'Leroy', 'Sonia', 'Bachelor Design - 2ème année', NULL, 'sonia@ynov.com', 'hash456', '21 ans. Étudiante en Design. Je me constitue un portfolio solide et je cherche activement un stage.', '2026-03-30 13:50:39'),
(3, 'leo_business', 'Garin', 'Léo', 'Bachelor Business - 2ème année', NULL, 'leo@ynov.com', 'hash789', '20 ans. Organise des événements pour le BDE. Frustré par les emails internes qui sont peu lus.', '2026-03-30 13:50:39');

-- 2. Données pour la table `posts`
INSERT INTO `posts` (`id`, `user_id`, `content`, `created_at`) VALUES
(1, 1, 'Hello ! La charge de travail technique en B1 Info est intense en ce moment. Est-ce qu\'un étudiant en B2 serait dispo pour du peer-learning ?', '2026-03-30 13:50:59'),
(2, 1, 'Je bloque sur un algo de tri, si quelqu\'un a 10 minutes pour m\'expliquer le concept, je suis preneur ! #Entraide #B1Info', '2026-03-30 13:50:59'),
(3, 2, 'Je finalise mon portfolio ! Hâte de vous montrer mes derniers projets UI/UX. D\'ailleurs, je cherche un stage pour mai.', '2026-03-30 13:50:59'),
(4, 2, 'C\'est dommage qu\'on ne voit pas plus les projets des autres filières (Info, Business...), on pourrait faire de super collaborations !', '2026-03-30 13:50:59'),
(5, 3, 'On a un super événement en préparation avec le BDE, mais j\'ai l\'impression que personne ne lit les mails internes...', '2026-03-30 13:50:59');

-- 3. Données pour la table `news`
INSERT INTO `news` (`id`, `title`, `content`, `created_at`) VALUES
(1, 'Lancement de la plateforme de Peer-Learning', 'Bonne nouvelle pour les B1 ! Une nouvelle initiative de tutorat entre pairs est lancée.', '2026-03-30 13:52:55'),
(2, 'Semaine de l\'Inter-Filières : Créez vos équipes !', 'Le workshop inter-filières approche. C\'est le moment pour les étudiants de collaborer.', '2026-03-30 13:52:55'),
(3, 'Forum des Stages 2026', 'Plus de 30 entreprises partenaires seront présentes sur le campus le mois prochain.', '2026-03-30 13:52:55'),
(4, 'Note du BDE : Nouvelle application de communication', 'Pour pallier le manque de visibilité des emails, le BDE teste officiellement ce nouveau flux.', '2026-03-30 13:52:55'),
(5, 'Concours du Meilleur Portfolio', 'Le département Design organise un concours pour mettre en avant les créations des étudiants.', '2026-03-30 13:52:55');

-- Réactivation des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;