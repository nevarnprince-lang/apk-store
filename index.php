<?php
$status = "";
$jsonFile = 'data.json';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $dev = $_POST['dev'];
    $version = $_POST['version'];
    $desc = $_POST['desc'];
    $id = time();

    $iconDir = 'uploads/icons/';
    $apkDir = 'uploads/apks/';
    
    if (!is_dir($iconDir)) mkdir($iconDir, 0777, true);
    if (!is_dir($apkDir)) mkdir($apkDir, 0777, true);

    $iconPath = $iconDir . $id . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", basename($_FILES['icon']['name']));
    $apkPath = $apkDir . $id . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", basename($_FILES['apk']['name']));
    
    $size = round($_FILES['apk']['size'] / (1024 * 1024), 2) . ' MB';

    if (move_uploaded_file($_FILES['icon']['tmp_name'], $iconPath) && move_uploaded_file($_FILES['apk']['tmp_name'], $apkPath)) {
        
        $currentApps = [];
        if (file_exists($jsonFile)) {
            $currentApps = json_decode(file_get_contents($jsonFile), true) ?? [];
        }

        $newApp = [
            'id' => $id,
            'name' => $name,
            'dev' => $dev,
            'version' => $version,
            'size' => $size,
            'desc' => $desc,
            'icon' => $iconPath,
            'apk' => $apkPath
        ];

        $currentApps[] = $newApp;
        file_put_contents($jsonFile, json_encode($currentApps, JSON_PRETTY_PRINT));
        $status = "Success";
    } else {
        $status = "Error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Studio - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&family=Comfortaa:wght@500;700&display=swap');
        
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f5f5f0;
            color: #4a3728;
        }

        .heading-font { font-family: 'Comfortaa', cursive; }

        .input-coffee {
            background-color: #fffdfa;
            border: 2px solid #e9e4db;
            border-radius: 1.2rem;
            padding: 1rem;
            width: 100%;
            transition: all 0.3s;
        }

        .input-coffee:focus {
            outline: none;
            border-color: #d4a373;
            background-color: white;
        }

        .btn-coffee {
            background-color: #91765a;
            color: #fffdfa;
            border-radius: 1.2rem;
            font-weight: bold;
            transition: all 0.2s;
        }

        .btn-coffee:hover {
            background-color: #4a3728;
        }

        .file-input-wrapper {
            background: #fdfdfd;
            border: 2px dashed #e9e4db;
            border-radius: 1.2rem;
            padding: 1rem;
            text-align: center;
        }
    </style>
</head>
<body class="p-6">

    <div class="max-w-2xl mx-auto mt-6">
        <div class="flex justify-between items-center mb-10">
            <h1 class="heading-font text-3xl font-bold">Admin Bar Studio</h1>
            <a href="index.php" class="text-stone-400 font-bold hover:text-stone-800">← Back to Menu</a>
        </div>

        <?php if ($status == "Success"): ?>
            <div class="mb-8 p-4 bg-stone-800 text-stone-100 rounded-2xl font-bold text-center">
                App Published Successfully ☕
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-stone-100">
            <form action="admin.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2 ml-2">App Name</label>
                        <input type="text" name="name" required class="input-coffee">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2 ml-2">Developer</label>
                        <input type="text" name="dev" required class="input-coffee">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2 ml-2">Version</label>
                        <input type="text" name="version" required placeholder="e.g. 1.0.4" class="input-coffee">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2 ml-2">App Icon</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="icon" accept="image/*" required class="text-xs">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2 ml-2">APK File</label>
                    <div class="file-input-wrapper bg-stone-50 border-stone-200">
                        <input type="file" name="apk" accept=".apk" required class="text-xs w-full">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2 ml-2">Description</label>
                    <textarea name="desc" required class="input-coffee h-32 resize-none"></textarea>
                </div>

                <button type="submit" class="w-full py-5 btn-coffee text-lg shadow-lg shadow-stone-200 mt-4">
                    Brew & Publish ☕
                </button>
            </form>
        </div>
    </div>

</body>
</html>
