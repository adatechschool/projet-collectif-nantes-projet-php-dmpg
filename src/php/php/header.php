    <!-- Barre de navigation -->
    <div class="bg-blue-800 text-white w-64 p-6">
            <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
            
            <button> <a href="welcome.php" class="flex items-center py-2 px-3 hover:border border-solid rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Accueil </a> </button>
            <button> <a href="collection_list.php" class="flex items-center py-4 px-3 hover:border border-solid rounded-lg"><i class="fa-solid fa-list mr-3"></i> Liste des collectes</a> </button>
            <button> <a href="volunteer_list.php" class="flex items-center py-4 px-3 hover:border border-solid rounded-lg"><i class="fa-solid fa-clipboard-list mr-4"></i> Liste des bénévoles</a> </button>
            <button> <a href="collection_add.php" class="flex items-center py-4 px-3 hover:border border-solid rounded-lg"><i class="fas fa-plus-circle mr-3"></i> Ajouter une collecte</a> </button>
            <button> <a href="user_add.php" class="flex items-center py-4 px-3 hover:border border-solid rounded-lg"><i class="fas fa-user-plus mr-2.5"></i> Ajouter un bénévole</a> </button>
            <button> <a href="my_account.php" class="flex items-center py-4 px-3 hover:border border-solid rounded-lg"><i class="fas fa-cogs mr-2.5"></i> Mon compte</a> </button>
            <div class="mt-6">
                <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-4 rounded-lg shadow-md">
                    Déconnexion
                </button>
            </div>
    </div>