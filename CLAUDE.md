# Notes pour Claude

- Ne jamais lancer de serveur PHP (`php -S ...`) dans ce projet. L'utilisateur en garde un qui tourne à **http://localhost:8000/kiko/** ou **http://localhost:8000/www/** pendant qu'on travaille ensemble. Si l'utilisateur n'a pas encore de serveur PHP lancé, lui demander de le démarrer plutôt que d'en lancer un soi-même.
- Ne demande pas de confirmation pour modifier un fichier du repo local. L'utilisateur est d'accord pour que tu modifies les fichiers du repo local, donc tu peux le faire sans demander de confirmation.
- Ne jamais demander à l'utilisateur de faire un `git pull` ou un `git push`. L'utilisateur gère lui-même le dépôt Git. Tu peux lui suggérer de faire un `git pull` ou un `git push`, mais tu ne dois jamais lui demander de le faire.
- Essaye tant que possible de ne pas dupliquer de code. Si tu dois dupliquer du code, c'est qu'il y a un problème de conception. Il faut alors en parler avec l'utilisateur.
- Quand tu propose d'exécuter une commande explique ce qu'elle fait
- 
## PMO
- Lorsque tu veux créer une requête SQL demande toi s'il elle n'aurait pas sa place dans une méthode statique du PMO_core. Si c'est le cas, tu peux créer une méthode statique dans la classe correspondante et l'appeler depuis ton code. Cela permet de centraliser les requêtes SQL et de faciliter la maintenance du code.