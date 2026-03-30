<main class="max-w-7xl w-full mx-auto px-6 py-8 flex-grow">
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg overflow-hidden">
        <!-- Banner Profile -->
        <div class="h-56 bg-[#e2e2e2] flex items-center justify-center border-b border-gray-300 shadow-inner">
             <span class="text-gray-400 font-sans tracking-widest text-lg">[ bannière de profil ]</span>
        </div>
        
        <div class="px-8 pb-10 relative">
            <!-- Avatar overlapping banner -->
            <div class="-mt-16 mb-4">
                <div class="w-32 h-32 rounded-full bg-gray-300 border-4 border-white flex items-center justify-center shadow-md"></div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <!-- Left Sidebar (User Info & AI) -->
                <div class="lg:col-span-4 space-y-8">
                    <div>
                        <h1 class="text-2xl font-heading font-extrabold text-gray-900">Prénom Nom</h1>
                        <p class="text-sm text-gray-500 font-sans font-medium">Informatique - B2</p>
                    </div>
                    
                    <div class="flex gap-2.5">
                        <button class="bg-tech-blue-dark text-white px-6 py-2.5 rounded text-sm font-semibold shadow-sm flex-1 hover:bg-black transition">
                            Envoyer message
                        </button>
                        <button class="border border-gray-300 bg-white text-gray-600 px-4 py-2.5 rounded text-sm font-bold hover:bg-gray-50 transition shadow-sm">
                            -
                        </button>
                    </div>

                    <!-- BIO -->
                    <div>
                        <h3 class="text-xs font-heading font-bold text-gray-400 uppercase tracking-widest mb-3">Bio</h3>
                        <div class="bg-[#FAFAFA] border border-dashed border-gray-300 rounded p-4 space-y-2.5">
                            <div class="h-2.5 bg-gray-300 rounded w-full"></div>
                            <div class="h-2.5 bg-gray-300 rounded w-4/5"></div>
                            <div class="h-2.5 bg-gray-300 rounded w-2/3"></div>
                        </div>
                    </div>

                    <!-- COMPETENCES -->
                    <div>
                        <div class="border-t border-dashed border-ynov-orange mb-4"></div>
                        <h3 class="text-xs font-heading font-bold text-gray-400 uppercase tracking-widest mb-3">Compétences</h3>
                        <div class="flex flex-wrap gap-2.5">
                            <span class="px-4 py-1.5 rounded-full border border-gray-300 text-xs text-gray-600 font-sans shadow-sm bg-white">JavaScript</span>
                            <span class="px-4 py-1.5 rounded-full border border-gray-300 text-xs text-gray-600 font-sans shadow-sm bg-white">Node.js</span>
                            <span class="px-4 py-1.5 rounded-full border border-gray-300 text-xs text-gray-600 font-sans shadow-sm bg-white">MySQL</span>
                            <span class="px-4 py-1.5 rounded-full border border-gray-300 text-xs text-gray-600 font-sans shadow-sm bg-white">React</span>
                            <span class="px-4 py-1.5 rounded-full border border-gray-300 text-xs text-gray-400 font-sans shadow-sm border-dashed bg-white hover:bg-gray-50 cursor-pointer transition">+ Ajouter</span>
                        </div>
                    </div>

                    <!-- RESUME IA -->
                    <div class="pt-6 border-t border-dashed border-ynov-orange relative">
                        <h3 class="text-sm font-heading font-bold text-gray-900 mb-3 flex items-center gap-2">Résumé IA <span class="text-yellow-500">✨</span></h3>
                        
                        <div class="bg-blue-50/50 border border-dashed border-blue-400 rounded-lg p-5 mb-4 space-y-3">
                            <div class="h-3 bg-blue-300/60 rounded w-full"></div>
                            <div class="h-3 bg-blue-300/60 rounded w-3/4"></div>
                        </div>
                        
                        <button class="w-full bg-tech-blue-dark text-white px-4 py-2.5 rounded text-sm font-medium shadow-sm hover:bg-black transition">
                            Générer résumé (IA)
                        </button>
                        <div class="mt-4 flex justify-start items-center">
                            <span class="bg-white text-ynov-orange font-mono text-xs px-2 py-1 rounded border border-ynov-orange shadow-sm inline-block">
                                POST /ai/summarize - Gemini
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Main Column (Publications & Stats) -->
                <div class="lg:col-span-8">
                    
                    <h3 class="text-sm font-heading font-semibold text-gray-800 border-b border-gray-300 pb-2 mb-4">Publications</h3>
                    
                    <!-- Tabs (Tous, Projets, Recherches) -->
                    <div class="flex gap-2 mb-6">
                        <button class="bg-tech-blue-dark text-white px-5 py-2 rounded text-sm font-heading shadow-sm">Tous</button>
                        <button class="bg-white border border-gray-300 text-gray-500 px-5 py-2 rounded text-sm font-heading hover:bg-gray-50 transition shadow-sm">Projets</button>
                        <button class="bg-white border border-gray-300 text-gray-500 px-5 py-2 rounded text-sm font-heading hover:bg-gray-50 transition shadow-sm">Recherches</button>
                    </div>

                    <!-- User's Posts -->
                    <div class="space-y-6">
                        <!-- Post 1 (Text) -->
                        <div class="bg-white rounded border border-gray-300 p-6 shadow-sm">
                            <div class="flex justify-between items-start mb-5">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-full bg-gray-300"></div>
                                    <div class="h-3 bg-gray-300 rounded w-32"></div>
                                </div>
                                <span class="text-xs text-gray-400 font-medium tracking-wide">2 jours</span>
                            </div>
                            <div class="mb-6 space-y-2.5">
                                <div class="h-3 bg-gray-300 rounded w-full"></div>
                                <div class="h-3 bg-gray-300 rounded w-2/3"></div>
                            </div>
                            <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                                <div class="flex space-x-3">
                                    <span class="border border-gray-300 rounded px-3 py-1.5 text-xs text-gray-600 flex items-center gap-1 shadow-sm"><span class="text-ynov-orange font-emoji">👍</span> 12</span>
                                    <span class="border border-gray-300 rounded px-3 py-1.5 text-xs text-gray-600 flex items-center gap-1 shadow-sm"><span class="text-gray-400">💬</span> 3</span>
                                </div>
                                <button class="text-red-500 border border-red-300 hover:bg-red-50 px-4 py-1.5 rounded text-xs font-semibold shadow-sm transition">Supprimer</button>
                            </div>
                        </div>

                        <!-- Post 2 (Image) -->
                        <div class="bg-white rounded border border-gray-300 p-6 shadow-sm">
                            <div class="flex justify-between items-start mb-5">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-full bg-gray-300"></div>
                                    <div class="h-3 bg-gray-300 rounded w-28"></div>
                                </div>
                                <span class="text-xs text-gray-400 font-medium tracking-wide">5 jours</span>
                            </div>
                            <!-- Image Frame -->
                            <div class="w-full h-48 bg-[#dfdfdf] border border-gray-300 rounded flex items-center justify-center mb-5 inner-shadow">
                                <span class="text-gray-500 font-sans tracking-widest text-lg opacity-80">[ image ]</span>
                            </div>
                            <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                                <div class="flex space-x-3">
                                    <span class="border border-gray-300 rounded px-3 py-1.5 text-xs text-gray-600 flex items-center gap-1 shadow-sm"><span class="text-ynov-orange font-emoji">👍</span> 8</span>
                                    <span class="border border-gray-300 rounded px-3 py-1.5 text-xs text-gray-600 flex items-center gap-1 shadow-sm"><span class="text-gray-400">💬</span> 1</span>
                                </div>
                                <button class="text-red-500 border border-red-300 hover:bg-red-50 px-4 py-1.5 rounded text-xs font-semibold shadow-sm transition">Supprimer</button>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques de bas de page -->
                    <h3 class="text-sm font-heading font-semibold text-gray-800 border-b border-gray-300 pb-2 mt-10 mb-4">Statistiques</h3>
                    <div class="grid grid-cols-3 gap-6">
                        <div class="border border-dashed border-gray-300 bg-gray-50 rounded-lg p-5 text-center shadow-sm">
                            <div class="text-3xl font-extrabold text-ynov-orange">12</div>
                            <div class="text-xs text-gray-500 font-medium mt-1 uppercase tracking-widest">Publications</div>
                        </div>
                        <div class="border border-dashed border-gray-300 bg-gray-50 rounded-lg p-5 text-center shadow-sm">
                            <div class="text-3xl font-extrabold text-gray-800">48</div>
                            <div class="text-xs text-gray-500 font-medium mt-1 uppercase tracking-widest">Abonnés</div>
                        </div>
                        <div class="border border-dashed border-gray-300 bg-gray-50 rounded-lg p-5 text-center shadow-sm">
                            <div class="text-3xl font-extrabold text-gray-800">5</div>
                            <div class="text-xs text-gray-500 font-medium mt-1 uppercase tracking-widest">Compétences</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
