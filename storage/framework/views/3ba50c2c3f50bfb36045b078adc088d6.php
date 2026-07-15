<?php
$brand = app(\App\Settings\GeneralSettings::class);
$primaryHsl = \App\Support\ColorHelper::hexToHslVar($brand->primary_color);
?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($brand->app_name); ?></title>
    <style>:root, html.dark { --primary: <?php echo e($primaryHsl); ?>; --ring: <?php echo e($primaryHsl); ?>; }</style>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <script>
        (function() {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen bg-background text-foreground antialiased">

<div class="grid min-h-screen lg:grid-cols-2">

    
    <div class="hidden lg:flex relative flex-col justify-between p-10 text-primary-foreground bg-gradient-to-br from-primary via-primary/90 to-primary/70">
        <div class="absolute inset-0 opacity-20 pointer-events-none"
             style="background-image: radial-gradient(circle at 20% 0%, white 0, transparent 30%), radial-gradient(circle at 80% 100%, white 0, transparent 30%);"></div>

        <div class="relative flex items-center gap-2 font-semibold">
            <?php if($brand->logo_path): ?>
                <img src="<?php echo e(asset('storage/'.$brand->logo_path)); ?>" alt="<?php echo e($brand->app_name); ?>" class="h-8 w-8 rounded-lg bg-white/10 object-contain p-0.5 backdrop-blur border border-white/20" />
            <?php else: ?>
                <div class="grid place-items-center h-8 w-8 rounded-lg bg-white/10 backdrop-blur border border-white/20"><?php echo e(mb_substr($brand->app_name, 0, 1)); ?></div>
            <?php endif; ?>
            <?php echo e($brand->app_name); ?>

        </div>

        <div class="relative">
            <blockquote class="space-y-2">
                <p class="text-xl leading-relaxed"><?php echo e($brand->app_description); ?></p>
                <footer class="text-sm opacity-80"><?php echo e($brand->app_name); ?></footer>
            </blockquote>
        </div>
    </div>

    
    <div class="flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-sm space-y-6">
            <div class="flex justify-center lg:hidden">
                <div class="flex items-center gap-2 font-semibold">
                    <?php if($brand->logo_path): ?>
                        <img src="<?php echo e(asset('storage/'.$brand->logo_path)); ?>" alt="<?php echo e($brand->app_name); ?>" class="h-8 w-8 rounded-lg object-contain" />
                    <?php else: ?>
                        <div class="grid place-items-center h-8 w-8 rounded-lg bg-primary text-primary-foreground"><?php echo e(mb_substr($brand->app_name, 0, 1)); ?></div>
                    <?php endif; ?>
                    <?php echo e($brand->app_name); ?>

                </div>
            </div>

            <?php echo e($slot); ?>

        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH D:\xampp_lite\xampp_lite_8_3\www\laracontact\resources\views/layouts/guest.blade.php ENDPATH**/ ?>