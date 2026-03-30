<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
        
        <div class="h-32 bg-gradient-to-r from-blue-500 to-purple-600"></div>

        <div class="px-8 pb-8">
            <div class="relative">
                <img class="w-32 h-32 rounded-full border-4 border-white absolute -top-16 left-0 shadow-lg object-cover" 
                     src="<?= $user['avatar'] ?? 'https://ui-avatars.com/api/?name=' . $user['username'] ?>" 
                     alt="Avatar">
                
                <div class="pt-20 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($user['username']) ?></h1>
                        <p class="text-gray-500">Membre depuis <?= date('M Y', strtotime($user['created_at'] ?? 'now')) ?></p>
                    </div>
                    <button id="editBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-semibold transition">
                        Modifier le profil
                    </button>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-700">À propos</h2>
                <p class="mt-2 text-gray-600 leading-relaxed">
                    <?= nl2br(htmlspecialchars($user['bio'] ?? 'Aucune biographie pour le moment.')) ?>
                </p>
            </div>

            <hr class="my-8 border-gray-200">

            <form id="editForm" action="/profile/update" method="POST" class="hidden space-y-6">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
                        <input type="text" name="username" value="<?= $user['username'] ?>" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 bg-gray-50 border focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bio</label>
                        <textarea name="bio" rows="4" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 bg-gray-50 border focus:ring-blue-500"><?= $user['bio'] ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">URL de l'avatar</label>
                        <input type="text" name="avatar" value="<?= $user['avatar'] ?>" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 bg-gray-50 border focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold">Enregistrer</button>
                    <button type="button" id="cancelBtn" class="text-gray-500 underline">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const editBtn = document.getElementById('editBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const editForm = document.getElementById('editForm');

    editBtn.addEventListener('click', () => {
        editForm.classList.remove('hidden');
        editBtn.classList.add('hidden');
    });

    cancelBtn.addEventListener('click', () => {
        editForm.classList.add('hidden');
        editBtn.classList.remove('hidden');
    });
</script>