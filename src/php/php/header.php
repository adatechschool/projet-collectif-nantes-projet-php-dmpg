    <!-- Barre de navigation -->
    <div class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 to-blue-500 w-64 p-6">
            <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
            <a href="welcome.php" class="flex items-center py-2 px-3 hover:border border-solid rounded-lg" aria-label="Accueil"><i class="fas fa-tachometer-alt mr-3"></i> Accueil </a>
            <a href="collection_list.php" class="flex items-center py-4 px-3 hover:border border-solid rounded-lg" aria-label="Liste des collectes"><i class="fa-solid fa-list mr-3"></i> Liste des collectes</a>
            <a href="volunteer_list.php" class="flex items-center py-4 px-3 hover:border border-solid rounded-lg" aria-label="Liste des bénévoles"><i class="fa-solid fa-clipboard-list mr-4"></i> Liste des bénévoles</a> 
            <a href="collection_add.php" class="flex items-center py-4 px-3 hover:border border-solid rounded-lg" aria-label="Ajouter une collecte"><i class="fas fa-plus-circle mr-3"></i> Ajouter une collecte</a> 
            <a href="user_add.php" class="flex items-center py-4 px-3 hover:border border-solid rounded-lg" aria-label="Ajouter un bénévole"><i class="fas fa-user-plus mr-2.5"></i> Ajouter un bénévole</a> 
            <a href="my_account.php" class="flex items-center py-4 px-3 hover:border border-solid rounded-lg" aria-label="Mon compte"><i class="fas fa-cogs mr-2.5"></i> Mon compte</a> 
            <div class="mt-6">
                <button onclick="logout()" class="font-bold border border-solid border-red-600 text-white px-4 py-2 rounded-lg shadow-lg  duration-200">
                    Déconnexion
                </button>
            </div>
    </div>