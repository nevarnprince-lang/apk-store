<?php
$jsonFile = 'data.json';
$apps = [];
if (file_exists($jsonFile)) {
    $apps = json_decode(file_get_contents($jsonFile), true) ?? [];
}

$viewApp = null;
if (isset($_GET['id'])) {
    foreach ($apps as $app) {
        if ($app['id'] == $_GET['id']) {
            $viewApp = $app;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee APK Bar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&family=Comfortaa:wght@500;700&display=swap');
        
        :root {
            --latte: #f5f5f0;
            --cream: #fffdfa;
            --mocha: #91765a;
            --espresso: #4a3728;
            --caramel: #d4a373;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: var(--latte);
            color: var(--espresso);
        }

        .heading-font { font-family: 'Comfortaa', cursive; }

        .coffee-card {
            background: var(--cream);
            border: 2px solid #e9e4db;
            transition: all 0.3s ease;
        }

        .coffee-card:hover {
            transform: translateY(-4px);
            border-color: var(--caramel);
            box-shadow: 0 10px 20px rgba(74, 55, 40, 0.05);
        }

        .btn-coffee {
            background-color: var(--mocha);
            color: var(--cream);
            transition: all 0.2s;
        }

        .btn-coffee:hover {
            background-color: var(--espresso);
            transform: scale(1.02);
        }

        .custom-blur {
            backdrop-filter: blur(8px);
            background: rgba(255, 253, 250, 0.7);
        }
    </style>
</head>
<body class="pb-20">

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 px-6 py-5 custom-blur border-b border-stone-200">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="index.php" class="heading-font text-2xl font-bold flex items-center gap-2">
                <span class="text-3xl">☕</span> 
                <span style="color: var(--espresso)">APK Bar</span>
            </a>
            <a href="admin.php" class="text-sm font-bold opacity-70 hover:opacity-100 flex items-center gap-1">
                Admin <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 mt-10">
        
        <?php if ($viewApp): ?>
            <!-- Detail View -->
            <div class="animate-in fade-in slide-in-from-bottom-2 duration-500">
                <a href="index.php" class="inline-flex items-center text-stone-500 font-bold mb-8 hover:text-stone-800 transition-colors">
                    ← Back to menu
                </a>
                
                <div class="bg-white rounded-[2.5rem] p-8 md:p-12 border border-stone-100 shadow-sm flex flex-col md:flex-row gap-10 items-center md:items-start">
                    <img src="<?php echo $viewApp['icon']; ?>" class="w-40 h-40 rounded-[2rem] shadow-sm bg-stone-50 object-cover p-2 border border-stone-100">
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="heading-font text-4xl font-bold mb-2"><?php echo htmlspecialchars($viewApp['name']); ?></h1>
                        <p class="text-stone-400 font-semibold mb-6">by <?php echo htmlspecialchars($viewApp['dev']); ?></p>
                        
                        <div class="flex flex-wrap justify-center md:justify-start gap-4 mb-8">
                            <span class="bg-stone-100 px-4 py-1.5 rounded-full text-xs font-bold text-stone-500 uppercase tracking-widest">Version <?php echo htmlspecialchars($viewApp['version']); ?></span>
                            <span class="bg-stone-100 px-4 py-1.5 rounded-full text-xs font-bold text-stone-500 uppercase tracking-widest"><?php echo htmlspecialchars($viewApp['size']); ?></span>
                        </div>

                        <a href="<?php echo $viewApp['apk']; ?>" download class="btn-coffee inline-flex items-center gap-3 px-12 py-4 rounded-full font-bold text-lg shadow-md">
                            Download APK
                        </a>
                    </div>
                </div>

                <div class="mt-8 px-4">
                    <h3 class="heading-font text-xl font-bold mb-4 border-b border-stone-200 pb-2">Description</h3>
                    <p class="text-stone-600 leading-relaxed text-lg whitespace-pre-line"><?php echo htmlspecialchars($viewApp['desc']); ?></p>
                </div>
            </div>

        <?php else: ?>
            <!-- Shop List -->
            <header class="mb-12 text-center md:text-left">
                <h2 class="heading-font text-3xl font-bold mb-2">Fresh Brews</h2>
                <p class="text-stone-400 font-medium">Hand-picked APKs just for you.</p>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($apps)): ?>
                    <div class="col-span-full text-center py-20 bg-stone-50 rounded-3xl border-2 border-dashed border-stone-200">
                        <p class="text-stone-400 font-bold">The menu is empty today...</p>
                    </div>
                <?php endif; ?>

                <?php foreach (array_reverse($apps) as $app): ?>
                    <a href="index.php?id=<?php echo $app['id']; ?>" class="coffee-card rounded-[2rem] p-6 flex flex-col items-center text-center">
                        <div class="relative mb-4">
                            <img src="<?php echo $app['icon']; ?>" class="w-24 h-24 rounded-2xl bg-white object-cover">
                            <div class="absolute -bottom-2 -right-2 bg-white w-8 h-8 rounded-full flex items-center justify-center text-xs shadow-sm border border-stone-100">📦</div>
                        </div>
                        <h3 class="font-bold text-lg mb-1 truncate w-full"><?php echo htmlspecialchars($app['name']); ?></h3>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-tighter mb-4"><?php echo htmlspecialchars($app['dev']); ?></p>
                        <div class="mt-auto w-full py-2 bg-stone-100 rounded-full text-xs font-bold text-stone-500">
                            Details
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

</body>
</html>
