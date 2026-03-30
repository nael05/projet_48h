<main class="max-w-7xl w-full mx-auto px-6 py-8 flex-grow">
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex overflow-hidden" style="min-height: 70vh;">
        <!-- Left Sidebar: Contact List -->
        <div class="w-1/3 border-r border-gray-300 flex flex-col bg-white">
            <div class="p-5 border-b border-gray-300">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="w-4 h-4 text-tech-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded font-sans text-sm focus:outline-none focus:ring-1 focus:ring-tech-blue transition" placeholder="Rechercher une conv...">
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                <!-- Contact 1 (Active/Unread) -->
                <div class="flex items-center p-5 border-b border-gray-100 bg-blue-50/30 cursor-pointer border-l-4 border-l-tech-blue">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-full bg-gray-300"></div>
                        <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-baseline mb-1">
                            <h4 class="text-sm font-bold text-gray-900 font-sans tracking-wide">Alice Martin</h4>
                            <span class="text-xs text-gray-500 font-medium">14h30</span>
                        </div>
                        <p class="text-xs text-gray-700 font-semibold truncate flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-tech-blue"></span>
                            Tu peux m'aider sur le TP?
                        </p>
                    </div>
                </div>

                <!-- Contact 2 -->
                <div class="flex items-center p-5 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition border-l-4 border-l-transparent">
                    <div class="w-12 h-12 rounded-full bg-gray-300"></div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-baseline mb-1">
                            <h4 class="text-sm font-medium text-gray-700 font-sans tracking-wide">Bob Durand</h4>
                            <span class="text-xs text-gray-400 font-medium">Hier</span>
                        </div>
                        <div class="h-2.5 bg-gray-200 rounded w-2/3 mt-2.5"></div>
                    </div>
                </div>

                <!-- Contact 3 -->
                <div class="flex items-center p-5 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition border-l-4 border-l-transparent">
                    <div class="w-12 h-12 rounded-full bg-gray-300"></div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-baseline mb-1">
                            <h4 class="text-sm font-medium text-gray-700 font-sans tracking-wide">Clara Chen</h4>
                            <span class="text-xs text-gray-400 font-medium">Lun</span>
                        </div>
                        <div class="h-2.5 bg-gray-200 rounded w-1/2 mt-2.5"></div>
                    </div>
                </div>
                
                <!-- Contact 4 -->
                <div class="flex items-center p-5 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition border-l-4 border-l-transparent">
                    <div class="w-12 h-12 rounded-full bg-gray-300"></div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-baseline mb-1">
                            <h4 class="text-sm font-medium text-gray-700 font-sans tracking-wide">David Roy</h4>
                        </div>
                        <div class="h-2.5 bg-gray-200 rounded w-3/4 mt-2.5"></div>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                <span class="bg-white text-ynov-orange font-mono text-xs px-2 py-1 rounded border border-ynov-orange shadow-sm inline-block">GET /messages - polling 5s</span>
            </div>
        </div>

        <!-- Right Side: Chat Area -->
        <div class="w-2/3 flex flex-col bg-[#F9F9F9] relative">
            <!-- Chat Header -->
            <div class="px-6 py-4 bg-white border-b border-gray-300 flex justify-between items-center shadow-sm z-10 h-[89px]">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-300"></div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 leading-tight font-heading">Alice Martin</h3>
                        <p class="text-xs text-green-600 font-bold tracking-wide">En ligne</p>
                    </div>
                </div>
                <button class="border border-gray-300 bg-white text-gray-500 px-4 py-2 rounded text-xs font-bold hover:bg-gray-50 hover:text-gray-800 transition shadow-sm uppercase tracking-wider">
                    Voir le profil →
                </button>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 p-8 overflow-y-auto space-y-5 flex flex-col">
                <div class="text-center mb-6">
                    <span class="text-xs text-gray-400 font-bold bg-gray-200/60 px-4 py-1.5 rounded-full uppercase tracking-wider">Aujourd'hui 14h20</span>
                </div>

                <!-- Msg received -->
                <div class="flex justify-start">
                    <div class="bg-white border border-gray-200 text-gray-700 font-medium px-5 py-3 rounded-2xl rounded-tl-sm text-sm shadow-sm max-w-md font-sans">
                        Salut ! T'as avancé sur le projet ?
                    </div>
                </div>

                <!-- Msg sent -->
                <div class="flex justify-end">
                    <div class="bg-gray-800 text-white px-5 py-3 rounded-2xl rounded-tr-sm text-sm shadow-sm max-w-md font-sans font-medium">
                        Oui, j'ai fini les models hier soir !
                    </div>
                </div>

                <!-- Msg received -->
                <div class="flex justify-start">
                    <div class="bg-white border border-gray-200 text-gray-700 font-medium px-5 py-3 rounded-2xl rounded-tl-sm text-sm shadow-sm max-w-md font-sans">
                        Super, tu peux m'aider sur le TP Node ?
                    </div>
                </div>

                <!-- Msg sent -->
                <div class="flex justify-end">
                    <div class="bg-gray-800 text-white px-5 py-3 rounded-2xl rounded-tr-sm text-sm shadow-sm max-w-md font-sans font-medium">
                        Bien sûr, on se retrouve à 16h ?
                    </div>
                </div>

                <!-- Msg received -->
                <div class="flex justify-start mb-1">
                    <div class="bg-[#EFF6FF] border border-[#BFDBFE] text-[#1E3A8A] font-medium px-5 py-3 rounded-2xl rounded-tl-sm text-sm shadow-sm max-w-md font-sans">
                        Parfait, à tout à l'heure 🔥
                    </div>
                </div>
                <div class="pr-2 flex justify-end items-center gap-1.5 opacity-70">
                    <span class="text-xs text-gray-500 font-semibold tracking-wide">Lu</span> 
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="p-6 bg-white border-t border-gray-300">
                <div class="flex items-center gap-3">
                    <button class="text-gray-400 hover:text-gray-600 transition p-2.5 border border-gray-300 rounded bg-gray-50 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                    </button>
                    <input type="text" class="flex-1 border border-gray-300 rounded p-3 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 bg-gray-50 font-sans shadow-inner placeholder-gray-400" placeholder="Écrire un message...">
                    <button class="bg-tech-blue-dark text-white px-8 py-3 rounded text-sm font-bold shadow-sm hover:bg-black transition font-heading">Envoyer</button>
                </div>
                <div class="mt-4 flex">
                    <span class="bg-white text-ynov-orange font-mono text-xs px-2 py-1 rounded border border-ynov-orange shadow-sm inline-block">POST /messages - MessageModel.send()</span>
                </div>
            </div>
        </div>
    </div>
</main>
