<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['contact' => null, 'groups' => collect(), 'tags' => collect()]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['contact' => null, 'groups' => collect(), 'tags' => collect()]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$selectedTagIds = $contact?->tags->pluck('id')->all() ?? old('tags', []);
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    
    <div class="lg:col-span-2 space-y-4">

        
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php if (isset($component)) { $__componentOriginalac05ab5900e4a61633d685620e23e750 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalac05ab5900e4a61633d685620e23e750 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Basic information <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $attributes = $__attributesOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__attributesOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $component = $__componentOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__componentOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'space-y-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4']); ?>

                
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <?php if (isset($component)) { $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.avatar','data' => ['name' => $contact?->name ?? 'New','src' => $contact?->photo ? asset('storage/'.$contact->photo) : null,'size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($contact?->name ?? 'New'),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($contact?->photo ? asset('storage/'.$contact->photo) : null),'size' => 'lg']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2)): ?>
<?php $attributes = $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2; ?>
<?php unset($__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2)): ?>
<?php $component = $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2; ?>
<?php unset($__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2); ?>
<?php endif; ?>
                    </div>
                    <div class="space-y-1 flex-1 min-w-0">
                        <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => ['for' => 'photo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'photo']); ?>Profile photo (DP) <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                        <input id="photo" name="photo" type="file" accept="image/*"
                               class="block w-full max-w-full text-sm text-muted-foreground file:mr-3 file:rounded-md file:border file:border-input file:bg-background file:px-3 file:py-1 file:text-sm file:font-medium file:cursor-pointer hover:file:bg-accent" />
                        <p class="text-xs text-muted-foreground">JPG, PNG up to 2 MB</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5 sm:col-span-2">
                        <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => ['for' => 'phone']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'phone']); ?>Phone / Number <span class="text-destructive">*</span> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                        <input type="hidden" id="phone_country" name="phone_country" value="<?php echo e(old('phone_country', $contact?->phone_country ?: 'in')); ?>" />
                        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['id' => 'phone','name' => 'phone','value' => ''.e(old('phone', $contact?->phone ?: $contact?->number)).'','placeholder' => '98765 43210','required' => true,'autofocus' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'phone','name' => 'phone','value' => ''.e(old('phone', $contact?->phone ?: $contact?->number)).'','placeholder' => '98765 43210','required' => true,'autofocus' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-destructive"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="space-y-1.5 sm:col-span-2">
                        <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => ['for' => 'name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'name']); ?>Name <span class="text-destructive">*</span> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['id' => 'name','name' => 'name','value' => ''.e(old('name', $contact?->name)).'','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'name','name' => 'name','value' => ''.e(old('name', $contact?->name)).'','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-destructive"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="space-y-1.5">
                        <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => ['for' => 'email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'email']); ?>Email <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['id' => 'email','name' => 'email','type' => 'email','value' => ''.e(old('email', $contact?->email)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'email','name' => 'email','type' => 'email','value' => ''.e(old('email', $contact?->email)).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-destructive"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="space-y-1.5">
                        <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => ['for' => 'city']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'city']); ?>City <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['id' => 'city','name' => 'city','value' => ''.e(old('city', $contact?->city)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'city','name' => 'city','value' => ''.e(old('city', $contact?->city)).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
                    </div>

                    <div class="space-y-1.5 sm:col-span-2">
                        <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => ['for' => 'address']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'address']); ?>Address <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal62d1193389a71cd99ff302a00abbf991 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal62d1193389a71cd99ff302a00abbf991 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.textarea','data' => ['id' => 'address','name' => 'address','rows' => '2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'address','name' => 'address','rows' => '2']); ?><?php echo e(old('address', $contact?->address)); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $attributes = $__attributesOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__attributesOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $component = $__componentOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__componentOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
                    </div>

                    
                    <?php if(auth()->user()->isSuperAdmin()): ?>
                        <div class="space-y-1.5 sm:col-span-2">
                            <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => ['for' => 'admin_comment']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'admin_comment']); ?>Comment <span class="text-xs font-normal text-muted-foreground">(visible to all roles — only Super Admin can edit, max 100 characters)</span> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal62d1193389a71cd99ff302a00abbf991 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal62d1193389a71cd99ff302a00abbf991 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.textarea','data' => ['id' => 'admin_comment','name' => 'admin_comment','rows' => '2','maxlength' => '100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'admin_comment','name' => 'admin_comment','rows' => '2','maxlength' => '100']); ?><?php echo e(old('admin_comment', $contact?->admin_comment)); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $attributes = $__attributesOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__attributesOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $component = $__componentOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__componentOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
                            <?php $__errorArgs = ['admin_comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-destructive"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php elseif($contact?->admin_comment): ?>
                        <div class="space-y-1.5 sm:col-span-2">
                            <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Comment <span class="text-xs font-normal text-muted-foreground">(only Super Admin can edit)</span> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                            <p class="rounded-md border border-input bg-muted/30 px-3 py-2 text-sm whitespace-pre-line"><?php echo e($contact->admin_comment); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $attributes = $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $component = $__componentOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

        
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php if (isset($component)) { $__componentOriginalac05ab5900e4a61633d685620e23e750 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalac05ab5900e4a61633d685620e23e750 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Description <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-description','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-description'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Rich-text description for this contact. <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459)): ?>
<?php $attributes = $__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459; ?>
<?php unset($__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459)): ?>
<?php $component = $__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459; ?>
<?php unset($__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $attributes = $__attributesOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__attributesOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $component = $__componentOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__componentOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['xData' => 'htmlEditor(\'description_html\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-data' => 'htmlEditor(\'description_html\')']); ?>
                
                <div class="flex flex-wrap gap-1 mb-2 p-1 rounded-md border border-input bg-muted/30">
                    <button type="button" @click="exec('bold')" title="Bold" class="h-7 w-7 grid place-items-center rounded text-sm font-bold hover:bg-accent">B</button>
                    <button type="button" @click="exec('italic')" title="Italic" class="h-7 w-7 grid place-items-center rounded text-sm italic hover:bg-accent">I</button>
                    <button type="button" @click="exec('underline')" title="Underline" class="h-7 w-7 grid place-items-center rounded text-sm underline hover:bg-accent">U</button>
                    <div class="w-px bg-border mx-1"></div>
                    <button type="button" @click="exec('insertUnorderedList')" title="Bullet list" class="h-7 w-7 grid place-items-center rounded hover:bg-accent text-xs">• —</button>
                    <button type="button" @click="exec('insertOrderedList')" title="Numbered list" class="h-7 w-7 grid place-items-center rounded hover:bg-accent text-xs">1.</button>
                    <div class="w-px bg-border mx-1"></div>
                    <button type="button" @click="exec('removeFormat')" title="Clear formatting" class="h-7 px-2 rounded hover:bg-accent text-xs text-muted-foreground">Clear</button>
                </div>
                
                <div x-ref="editor"
                     contenteditable="true"
                     @input="sync"
                     class="min-h-[140px] rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-ring prose prose-sm max-w-none"
                ><?php echo old('description_html', $contact?->description_html); ?></div>
                <input type="hidden" name="description_html" x-ref="hidden" value="<?php echo e(old('description_html', $contact?->description_html)); ?>" />
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $attributes = $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $component = $__componentOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

        
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php if (isset($component)) { $__componentOriginalac05ab5900e4a61633d685620e23e750 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalac05ab5900e4a61633d685620e23e750 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Quick notes <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $attributes = $__attributesOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__attributesOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $component = $__componentOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__componentOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginal62d1193389a71cd99ff302a00abbf991 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal62d1193389a71cd99ff302a00abbf991 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.textarea','data' => ['id' => 'notes','name' => 'notes','rows' => '4','placeholder' => 'Short notes to remember...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'notes','name' => 'notes','rows' => '4','placeholder' => 'Short notes to remember...']); ?><?php echo e(old('notes', $contact ? ($contact->getAttributes()['notes'] ?? '') : '')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $attributes = $__attributesOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__attributesOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $component = $__componentOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__componentOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $attributes = $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $component = $__componentOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

        
        <?php
            $initialCustom = old('custom_fields_keys')
                ? collect(old('custom_fields_keys'))->map(fn ($k, $i) => ['key' => $k, 'value' => old('custom_fields_values')[$i] ?? ''])->all()
                : collect($contact?->custom_fields ?? [])->map(fn ($v, $k) => ['key' => (string) $k, 'value' => is_scalar($v) ? (string) $v : json_encode($v)])->values()->all();
        ?>
        <div x-data='{
            rows: <?php echo json_encode($initialCustom, 15, 512) ?>,
            add() { this.rows.push({ key: "", value: "" }); this.$nextTick(() => this.$refs.list?.lastElementChild?.querySelector("input")?.focus()); },
            remove(i) { this.rows.splice(i, 1); }
        }'>
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginalac05ab5900e4a61633d685620e23e750 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalac05ab5900e4a61633d685620e23e750 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-header','data' => ['class' => 'flex flex-row items-center justify-between']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'flex flex-row items-center justify-between']); ?>
                    <div>
                        <?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Custom fields <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-description','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-description'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Extra data that doesn't fit standard fields. <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459)): ?>
<?php $attributes = $__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459; ?>
<?php unset($__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459)): ?>
<?php $component = $__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459; ?>
<?php unset($__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459); ?>
<?php endif; ?>
                    </div>
                    <button type="button" @click="add" class="inline-flex items-center gap-1 h-7 px-2 rounded-md text-xs font-medium border border-input bg-background hover:bg-accent">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add field
                    </button>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $attributes = $__attributesOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__attributesOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $component = $__componentOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__componentOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <div x-show="rows.length === 0" x-cloak class="text-sm text-muted-foreground text-center py-4">No custom fields.</div>
                    <div x-ref="list" class="space-y-2">
                        <template x-for="(row, i) in rows" :key="i">
                            <div class="flex flex-wrap gap-2 items-start">
                                <input type="text" name="custom_fields_keys[]" x-model="row.key" placeholder="Label"
                                       class="flex-1 min-w-[140px] h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-sm focus-ring" />
                                <input type="text" name="custom_fields_values[]" x-model="row.value" placeholder="Value"
                                       class="flex-[2] min-w-[180px] h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-sm focus-ring" />
                                <button type="button" @click="remove(i)" class="h-9 w-9 grid place-items-center rounded-md border border-input text-muted-foreground hover:text-destructive hover:border-destructive">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $attributes = $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $component = $__componentOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
        </div>

        
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['xData' => '{ open: '.e($contact && ($contact->facebook || $contact->twitter || $contact->linkedin) ? 'true' : 'false').' }']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-data' => '{ open: '.e($contact && ($contact->facebook || $contact->twitter || $contact->linkedin) ? 'true' : 'false').' }']); ?>
            <?php if (isset($component)) { $__componentOriginalac05ab5900e4a61633d685620e23e750 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalac05ab5900e4a61633d685620e23e750 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-header','data' => ['class' => 'cursor-pointer select-none flex flex-row items-center justify-between','@click' => 'open = !open']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'cursor-pointer select-none flex flex-row items-center justify-between','@click' => 'open = !open']); ?>
                <div>
                    <?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Social media <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-description','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-description'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Optional links and handles. <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459)): ?>
<?php $attributes = $__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459; ?>
<?php unset($__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459)): ?>
<?php $component = $__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459; ?>
<?php unset($__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459); ?>
<?php endif; ?>
                </div>
                <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $attributes = $__attributesOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__attributesOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $component = $__componentOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__componentOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['xShow' => 'open','xTransition' => true,'class' => 'grid grid-cols-1 sm:grid-cols-3 gap-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-show' => 'open','x-transition' => true,'class' => 'grid grid-cols-1 sm:grid-cols-3 gap-4']); ?>
                <div class="space-y-1.5">
                    <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => ['for' => 'twitter']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'twitter']); ?>X / Twitter <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['id' => 'twitter','name' => 'twitter','value' => ''.e(old('twitter', $contact?->twitter)).'','placeholder' => '@username']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'twitter','name' => 'twitter','value' => ''.e(old('twitter', $contact?->twitter)).'','placeholder' => '@username']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
                </div>
                <div class="space-y-1.5">
                    <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => ['for' => 'linkedin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'linkedin']); ?>LinkedIn <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['id' => 'linkedin','name' => 'linkedin','value' => ''.e(old('linkedin', $contact?->linkedin)).'','placeholder' => 'username']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'linkedin','name' => 'linkedin','value' => ''.e(old('linkedin', $contact?->linkedin)).'','placeholder' => 'username']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
                </div>
                <div class="space-y-1.5">
                    <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => ['for' => 'facebook']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'facebook']); ?>Facebook <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['id' => 'facebook','name' => 'facebook','value' => ''.e(old('facebook', $contact?->facebook)).'','placeholder' => 'username']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'facebook','name' => 'facebook','value' => ''.e(old('facebook', $contact?->facebook)).'','placeholder' => 'username']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $attributes = $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $component = $__componentOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
    </div>

    
    <div class="space-y-4">

        
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['xData' => '{
            rating: '.e(old('rating', $contact?->rating ?? 0)).',
            hover: 0,
            set(v) { this.rating = v; document.getElementById(\'rating-input\').value = v; }
        }']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-data' => '{
            rating: '.e(old('rating', $contact?->rating ?? 0)).',
            hover: 0,
            set(v) { this.rating = v; document.getElementById(\'rating-input\').value = v; }
        }']); ?>
            <?php if (isset($component)) { $__componentOriginalac05ab5900e4a61633d685620e23e750 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalac05ab5900e4a61633d685620e23e750 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Rating <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-description','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-description'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Rate this contact out of 5. <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459)): ?>
<?php $attributes = $__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459; ?>
<?php unset($__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459)): ?>
<?php $component = $__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459; ?>
<?php unset($__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $attributes = $__attributesOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__attributesOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $component = $__componentOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__componentOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <div class="flex items-center gap-1">
                    <template x-for="i in 5" :key="i">
                        <button type="button"
                                @click="set(i)"
                                @mouseenter="hover = i"
                                @mouseleave="hover = 0"
                                class="h-8 w-8 grid place-items-center transition-colors">
                            <svg class="h-6 w-6 transition-colors"
                                 :style="(hover || rating) >= i ? 'color:#f59e0b;fill:#f59e0b' : 'color:#d1d5db;fill:transparent'"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </button>
                    </template>
                    <span class="ml-2 text-sm text-muted-foreground" x-text="rating ? rating + '/5' : 'Not rated'"></span>
                </div>
                <input type="hidden" name="rating" id="rating-input" value="<?php echo e(old('rating', $contact?->rating ?? 0)); ?>" />
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $attributes = $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $component = $__componentOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

        
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php if (isset($component)) { $__componentOriginalac05ab5900e4a61633d685620e23e750 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalac05ab5900e4a61633d685620e23e750 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Organize <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $attributes = $__attributesOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__attributesOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $component = $__componentOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__componentOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'space-y-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-4']); ?>
                <div class="space-y-1.5">
                    <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.label','data' => ['for' => 'group_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'group_id']); ?>Group <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                    <select id="group_id" name="group_id" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-ring">
                        <option value="">No group</option>
                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($group->id); ?>" <?php if(old('group_id', $contact?->group_id) == $group->id): echo 'selected'; endif; ?>><?php echo e($group->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $attributes = $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $component = $__componentOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

        
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['xData' => '{
            busy: false, suggested: [], shown: false, fake: false,
            async suggest() {
                const text = (document.getElementById(\'notes\')?.value || \'\') + \'\n\' + (document.querySelector(\'[name=company]\')?.value || \'\') + \'\n\' + (document.querySelector(\'[name=job_title]\')?.value || \'\');
                if (text.trim().length < 5) { window.dispatchEvent(new CustomEvent(\'toast\', { detail: { type: \'error\', message: \'Add notes, company, or job title first.\' }})); return; }
                this.busy = true;
                try {
                    const r = await fetch(\''.e(route('contacts.suggest-tags')).'\', { method: \'POST\', headers: { \'X-CSRF-TOKEN\': document.querySelector(\'meta[name=csrf-token]\').content, \'Content-Type\': \'application/json\', \'Accept\': \'application/json\' }, body: JSON.stringify({ text }) });
                    const data = await r.json();
                    this.suggested = (data.tags || []).map(t => String(t.id));
                    this.fake = data.fake; this.shown = true;
                    if (!this.suggested.length) window.dispatchEvent(new CustomEvent(\'toast\', { detail: { message: \'No relevant tags found.\' }}));
                } finally { this.busy = false; }
            },
            apply() { this.suggested.forEach(id => { const cb = document.querySelector(`input[name=\'tags[]\'][value=\'${id}\']`); if (cb && !cb.checked) cb.checked = true; }); window.dispatchEvent(new CustomEvent(\'toast\', { detail: { type: \'success\', message: \'Suggested tags applied.\' }})); },
            isSuggested(id) { return this.suggested.includes(String(id)); }
        }']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-data' => '{
            busy: false, suggested: [], shown: false, fake: false,
            async suggest() {
                const text = (document.getElementById(\'notes\')?.value || \'\') + \'\n\' + (document.querySelector(\'[name=company]\')?.value || \'\') + \'\n\' + (document.querySelector(\'[name=job_title]\')?.value || \'\');
                if (text.trim().length < 5) { window.dispatchEvent(new CustomEvent(\'toast\', { detail: { type: \'error\', message: \'Add notes, company, or job title first.\' }})); return; }
                this.busy = true;
                try {
                    const r = await fetch(\''.e(route('contacts.suggest-tags')).'\', { method: \'POST\', headers: { \'X-CSRF-TOKEN\': document.querySelector(\'meta[name=csrf-token]\').content, \'Content-Type\': \'application/json\', \'Accept\': \'application/json\' }, body: JSON.stringify({ text }) });
                    const data = await r.json();
                    this.suggested = (data.tags || []).map(t => String(t.id));
                    this.fake = data.fake; this.shown = true;
                    if (!this.suggested.length) window.dispatchEvent(new CustomEvent(\'toast\', { detail: { message: \'No relevant tags found.\' }}));
                } finally { this.busy = false; }
            },
            apply() { this.suggested.forEach(id => { const cb = document.querySelector(`input[name=\'tags[]\'][value=\'${id}\']`); if (cb && !cb.checked) cb.checked = true; }); window.dispatchEvent(new CustomEvent(\'toast\', { detail: { type: \'success\', message: \'Suggested tags applied.\' }})); },
            isSuggested(id) { return this.suggested.includes(String(id)); }
        }']); ?>
            <?php if (isset($component)) { $__componentOriginalac05ab5900e4a61633d685620e23e750 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalac05ab5900e4a61633d685620e23e750 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-header','data' => ['class' => 'flex flex-row items-center justify-between']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'flex flex-row items-center justify-between']); ?>
                <?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Tags <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
                <?php if($tags->isNotEmpty()): ?>
                    <button type="button" @click="suggest" :disabled="busy" class="inline-flex items-center gap-1 h-7 px-2 rounded-md text-xs font-medium border border-input bg-background hover:bg-accent disabled:opacity-50">
                        <svg class="h-3 w-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span x-show="!busy">AI suggest</span>
                        <span x-show="busy" x-cloak>Thinking…</span>
                    </button>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $attributes = $__attributesOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__attributesOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $component = $__componentOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__componentOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if($tags->isEmpty()): ?>
                    <p class="text-sm text-muted-foreground">No tags yet. <a href="<?php echo e(route('tags.index')); ?>" class="underline">Create some</a> first.</p>
                <?php else: ?>
                    <div x-show="shown && suggested.length > 0" x-cloak class="mb-3 rounded-md border border-primary/30 bg-primary/5 p-2.5">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-medium text-primary" x-text="fake ? 'Suggested (regex)' : '✨ Claude suggests'"></span>
                            <button type="button" @click="apply" class="text-xs text-primary hover:underline">Apply all</button>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="cursor-pointer relative">
                                <input type="checkbox" name="tags[]" value="<?php echo e($tag->id); ?>" class="peer sr-only" <?php if(in_array($tag->id, $selectedTagIds)): echo 'checked'; endif; ?> />
                                <span :class="isSuggested(<?php echo e($tag->id); ?>) ? 'ring-2 ring-primary/40 ring-offset-1' : ''"
                                      class="inline-flex items-center rounded-md border border-input bg-background px-2.5 py-1 text-xs font-medium transition-all hover:bg-accent peer-checked:bg-primary peer-checked:text-primary-foreground peer-checked:border-primary">
                                    <?php echo e($tag->name); ?>

                                </span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $attributes = $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $component = $__componentOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-2">
    <a href="<?php echo e(url()->previous()); ?>" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium shadow-sm transition-colors hover:bg-accent focus-ring">Cancel</a>
    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?><?php echo e($contact ? 'Save changes' : 'Create contact'); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
</div>

<?php $__env->startPush('head'); ?>
<link rel="stylesheet" href="<?php echo e(asset('vendor/intl-tel-input/css/intlTelInput.min.css')); ?>" />
<style>
    .iti { width: 100%; }
    .iti__dropdown-content { background-color: hsl(var(--card)); border: 1px solid hsl(var(--border)); color: hsl(var(--foreground)); }
    .iti__country--highlight, .iti__country:hover { background-color: hsl(var(--accent)); }
    .iti__search-input { background-color: transparent; color: hsl(var(--foreground)); }
    .iti__dial-code { color: hsl(var(--muted-foreground)); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function htmlEditor(fieldName) {
    return {
        init() {
            this.$refs.editor.innerHTML = this.$refs.hidden.value || '';
        },
        exec(cmd) {
            document.execCommand(cmd, false, null);
            this.$refs.editor.focus();
            this.sync();
        },
        sync() {
            this.$refs.hidden.value = this.$refs.editor.innerHTML;
        }
    };
}
</script>
<script src="<?php echo e(asset('vendor/intl-tel-input/js/intlTelInputWithUtils.min.js')); ?>"></script>
<script>
(function () {
    const input = document.getElementById('phone');
    const countryField = document.getElementById('phone_country');
    if (!input || !countryField || !window.intlTelInput) return;

    const iti = window.intlTelInput(input, {
        initialCountry: countryField.value || 'in',
        separateDialCode: true,
        countryOrder: ['in', 'us', 'gb', 'ae', 'sa', 'sg'],
    });

    const syncCountry = function () {
        const c = iti.getSelectedCountry();
        if (c && c.iso2) countryField.value = c.iso2;
    };
    input.addEventListener('countrychange', syncCountry);
    // A legacy "+xx…" value can override the initial country during init.
    syncCountry();

    // Store bare digits — spaces from as-you-type formatting would break
    // the LIKE-based phone search.
    const form = input.closest('form');
    if (form) {
        form.addEventListener('submit', function () {
            input.value = input.value.replace(/[^0-9]/g, '');
        });
    }
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp_lite\xampp_lite_8_3\www\laracontact\resources\views/contacts/_form.blade.php ENDPATH**/ ?>