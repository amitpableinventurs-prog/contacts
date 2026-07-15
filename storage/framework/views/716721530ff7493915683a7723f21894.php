<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> Dashboard <?php $__env->endSlot(); ?>

    <?php $isClerk = auth()->user()->isClerk(); ?>

    <div class="flex items-start justify-center min-h-[60vh] pt-16">
        <div class="w-full max-w-lg space-y-6">
            <div class="text-center space-y-1">
                <h1 class="text-2xl font-bold tracking-tight">Search Contact</h1>
                <p class="text-sm text-muted-foreground">
                    <?php echo e($isClerk ? 'Enter a phone number to find a contact.' : 'Enter a phone number or name to find a contact.'); ?>

                </p>
            </div>

            <form method="GET" action="<?php echo e(route('contacts.index')); ?>" class="flex gap-2">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                    </svg>
                    <input
                        type="text"
                        name="<?php echo e($isClerk ? 'number' : 'q'); ?>"
                        autofocus
                        placeholder="<?php echo e($isClerk ? 'Phone number…' : 'Phone number or name…'); ?>"
                        class="flex h-11 w-full rounded-md border border-input bg-background pl-9 pr-4 text-sm shadow-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                    />
                </div>
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','class' => 'h-11 px-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'h-11 px-6']); ?>Search <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
            </form>

            <?php if (! ($isClerk)): ?>
                <div class="text-center">
                    <a href="<?php echo e(route('contacts.index')); ?>" class="text-sm text-muted-foreground hover:text-foreground underline underline-offset-4">View all contacts →</a>
                </div>
            <?php endif; ?>

            <?php if($isClerk && !empty($recentSearches)): ?>
                <div class="space-y-2">
                    <h2 class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Recent searches</h2>
                    <div class="divide-y rounded-md border">
                        <?php $__currentLoopData = $recentSearches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $search): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($search['contact_id'] ? route('contacts.show', $search['contact_id']) : route('contacts.index', ['number' => $search['number']])); ?>"
                               class="flex items-center justify-between gap-2 px-3 py-2.5 text-sm hover:bg-accent transition-colors">
                                <span class="min-w-0 truncate">
                                    <?php if($search['name']): ?>
                                        <span class="font-medium"><?php echo e($search['name']); ?></span>
                                        <span class="text-muted-foreground"> · <?php echo e($search['number']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted-foreground">No match for <?php echo e($search['number']); ?></span>
                                    <?php endif; ?>
                                </span>
                                <svg class="h-4 w-4 shrink-0 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH D:\xampp_lite\xampp_lite_8_3\www\laracontact\resources\views/dashboard.blade.php ENDPATH**/ ?>