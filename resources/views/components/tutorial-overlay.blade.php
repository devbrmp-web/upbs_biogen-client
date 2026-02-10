<div id="tutorialOverlay" class="fixed inset-0 z-[9999] bg-black/50 hidden items-center justify-center transition-opacity duration-300 backdrop-blur-sm" data-steps='@json($steps ?? [])'>
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 relative transform transition-all scale-100">
        <!-- Icon Header -->
        <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-green-600 rounded-full p-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <button onclick="closeTutorial()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <div id="tutorialContent" class="mt-6 text-center">
            <!-- Dynamic Content -->
        </div>

        <!-- Dots Indicator -->
        <div class="flex justify-center space-x-2 mt-6 mb-4" id="dotsIndicator">
            <!-- Dynamic Dots -->
        </div>

        <div class="flex flex-col gap-3">
            <div class="flex space-x-3 w-full">
                <button onclick="closeTutorial()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Lewati</button>
                <button id="nextBtn" onclick="nextStep()" class="flex-1 px-4 py-2.5 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-md hover:shadow-lg transition">Lanjut</button>
            </div>
            
            <label class="flex items-center justify-center space-x-2 text-xs text-gray-500 cursor-pointer hover:text-gray-700 transition">
                <input type="checkbox" id="dontShowAgain" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                <span>Jangan tampilkan lagi untuk halaman ini</span>
            </label>
        </div>
    </div>
</div>

<script>
    (function() {
        const overlayEl = document.getElementById('tutorialOverlay');
        const raw = overlayEl ? overlayEl.getAttribute('data-steps') : '[]';
        const tutorialSteps = (() => { try { return JSON.parse(raw || '[]'); } catch { return []; } })();
        let currentStep = 0;
        // Generate unique key based on URL path to track per-page tutorials
        const pageKey = 'tutorial_shown_' + (window.location.pathname === '/' ? 'home' : window.location.pathname.replace(/[^a-zA-Z0-9]/g, '_'));

        document.addEventListener('DOMContentLoaded', () => {
            // Check if there are steps and if user hasn't dismissed it forever
            if (tutorialSteps.length > 0 && localStorage.getItem(pageKey) !== 'true') {
                // Small delay for better UX
                setTimeout(showTutorial, 1000);
            }
        });

        window.showTutorial = function() {
            document.getElementById('tutorialOverlay').classList.remove('hidden');
            document.getElementById('tutorialOverlay').classList.add('flex');
            renderStep();
        }

        window.renderStep = function() {
            const step = tutorialSteps[currentStep];
            const content = `
                <h3 class="text-xl font-bold text-gray-800 mb-3">${step.title}</h3>
                <p class="text-gray-600 text-sm leading-relaxed">${step.content}</p>
            `;
            document.getElementById('tutorialContent').innerHTML = content;
            
            const nextBtn = document.getElementById('nextBtn');
            if (currentStep === tutorialSteps.length - 1) {
                nextBtn.textContent = 'Mengerti, Mulai!';
                nextBtn.onclick = finishTutorial;
            } else {
                nextBtn.textContent = 'Lanjut';
                nextBtn.onclick = nextStep;
            }

            // Render Dots
            let dotsHtml = '';
            for(let i=0; i<tutorialSteps.length; i++) {
                const colorClass = i === currentStep ? 'bg-green-600 w-6' : 'bg-gray-300 w-2';
                dotsHtml += `<div class="h-2 rounded-full transition-all duration-300 ${colorClass}"></div>`;
            }
            document.getElementById('dotsIndicator').innerHTML = dotsHtml;
        }

        window.nextStep = function() {
            if (currentStep < tutorialSteps.length - 1) {
                currentStep++;
                renderStep();
            }
        }

        window.finishTutorial = function() {
            if (document.getElementById('dontShowAgain').checked) {
                localStorage.setItem(pageKey, 'true');
            }
            closeTutorial();
        }

        window.closeTutorial = function() {
            const overlay = document.getElementById('tutorialOverlay');
            overlay.classList.remove('flex');
            overlay.classList.add('hidden');
            
            // If user clicked Skip, we might want to ask if they want to suppress it, 
            // but for now simple close. 
            // Optional: Auto-save "true" if they finish? 
            // The requirement says "Jika localStorage kosong... tutorial muncul". 
            // "Tambahkan tombol 'Jangan Tampilkan Lagi'".
            // So if they just close/skip, it might show again next session unless checked.
            if (document.getElementById('dontShowAgain').checked) {
                localStorage.setItem(pageKey, 'true');
            }
        }
    })();
</script>
