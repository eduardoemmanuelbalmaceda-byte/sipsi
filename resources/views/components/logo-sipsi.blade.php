<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <svg width="64" height="64" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-sm">
        <defs>
            <linearGradient id="brainGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" /> <stop offset="100%" style="stop-color:#10b981;stop-opacity:1" /> </linearGradient>
        </defs>

        <path d="M50,15 C30,15 15,32 15,50 C15,68 30,85 50,85 C55,85 62,82 68,78 C82,72 90,58 90,45 C90,28 75,15 50,15 Z" 
              stroke="url(#brainGradient)" 
              stroke-width="5" 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              fill="#f8fafc"/>

        <path d="M40,30 C32,35 30,48 35,58" stroke="#3b82f6" stroke-width="3" stroke-linecap="round" fill="none" opacity="0.6"/>
        <path d="M60,25 C70,30 75,45 70,60" stroke="#3b82f6" stroke-width="3" stroke-linecap="round" fill="none" opacity="0.6"/>
        
        <path d="M50,20 V80" stroke="url(#brainGradient)" stroke-width="2" stroke-dasharray="4 4" opacity="0.4"/>

        <circle cx="50" cy="50" r="4" fill="#10b981">
            <animate attributeName="opacity" values="1;0.5;1" dur="3s" repeatCount="indefinite" />
        </circle>
    </svg>

    <div class="flex flex-col leading-tight">
        <span class="text-2xl font-black tracking-tighter text-slate-800 uppercase">
            SIPSI
        </span>
        <span class="text-[10px] font-medium tracking-widest text-slate-500 uppercase">
            Psiquiatría Hospitalaria
        </span>
    </div>
</div>