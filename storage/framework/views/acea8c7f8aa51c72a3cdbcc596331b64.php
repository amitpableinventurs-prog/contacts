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
     <?php $__env->slot('header', null, []); ?> Contacts / Pending Approvals <?php $__env->endSlot(); ?>

    <div class="space-y-8">
        
        <div class="space-y-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">New contacts</h1>
                    <p class="text-sm text-muted-foreground">
                        Contacts submitted by Managers that require your approval before becoming active.
                    </p>
                </div>
                <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['class' => 'text-sm px-3 py-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-sm px-3 py-1']); ?><?php echo e($contacts->total()); ?> pending <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
            </div>

            <?php if($contacts->isEmpty()): ?>
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
                    <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'py-16 text-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'py-16 text-center']); ?>
                        <div class="mx-auto h-12 w-12 rounded-full bg-green-100 grid place-items-center mb-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-base">All caught up!</h3>
                        <p class="text-sm text-muted-foreground mt-1">No contacts waiting for approval.</p>
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
            <?php else: ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                            <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'p-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-4']); ?>
                                <div class="flex flex-wrap items-start gap-4">

                                    
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <?php if (isset($component)) { $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.avatar','data' => ['name' => $contact->name,'src' => $contact->photo ? asset('storage/'.$contact->photo) : null,'size' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($contact->name),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($contact->photo ? asset('storage/'.$contact->photo) : null),'size' => 'md']); ?>
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
                                        <div class="min-w-0">
                                            <div class="font-semibold text-base"><?php echo e($contact->name); ?></div>
                                            <div class="text-sm text-muted-foreground">
                                                <?php echo e(collect([$contact->email, $contact->phone])->filter()->join(' · ') ?: '—'); ?>

                                            </div>
                                            <?php if($contact->company): ?>
                                                <div class="text-xs text-muted-foreground"><?php echo e($contact->company); ?><?php if($contact->job_title): ?> · <?php echo e($contact->job_title); ?><?php endif; ?></div>
                                            <?php endif; ?>
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                <?php if($contact->group): ?>
                                                    <span class="inline-flex items-center gap-1 rounded-md border border-input px-1.5 py-0.5 text-xs">
                                                        <span class="h-1.5 w-1.5 rounded-full" style="background:<?php echo e($contact->group->color ?: '#a855f7'); ?>"></span>
                                                        <?php echo e($contact->group->name); ?>

                                                    </span>
                                                <?php endif; ?>
                                                <?php $__currentLoopData = $contact->tags->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['variant' => 'outline','class' => 'text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','class' => 'text-xs']); ?><?php echo e($tag->name); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="text-xs text-muted-foreground shrink-0 text-right space-y-0.5">
                                        <div>Submitted by <span class="font-medium text-foreground"><?php echo e($contact->owner?->name ?? '—'); ?></span></div>
                                        <div><?php echo e($contact->created_at->diffForHumans()); ?></div>
                                        <?php if($contact->notes): ?>
                                            <div class="mt-1 text-xs italic max-w-xs text-right"><?php echo e(\Illuminate\Support\Str::limit($contact->notes, 80)); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve-contacts')): ?>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a href="<?php echo e(route('contacts.show', $contact)); ?>" class="inline-flex h-8 items-center gap-1 rounded-md border border-input bg-background px-3 text-xs font-medium hover:bg-accent transition-colors">
                                            View
                                        </a>
                                        <form method="POST" action="<?php echo e(route('contacts.approve', $contact)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="inline-flex h-8 items-center gap-1 rounded-md bg-green-600 px-3 text-xs font-medium text-white hover:bg-green-700 transition-colors">
                                                ✓ Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('contacts.reject', $contact)); ?>" onsubmit="return confirm('Reject <?php echo e(addslashes($contact->name)); ?>?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="inline-flex h-8 items-center gap-1 rounded-md bg-red-600 px-3 text-xs font-medium text-white hover:bg-red-700 transition-colors">
                                                ✕ Reject
                                            </button>
                                        </form>
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if($contacts->hasPages()): ?>
                    <div><?php echo e($contacts->links()); ?></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        
        <div class="space-y-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Edits awaiting approval</h2>
                    <p class="text-sm text-muted-foreground">
                        Changes proposed by Managers to existing contacts. Nothing takes effect until approved.
                    </p>
                </div>
                <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['class' => 'text-sm px-3 py-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-sm px-3 py-1']); ?><?php echo e($editRequests->total()); ?> pending <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
            </div>

            <?php if($editRequests->isEmpty()): ?>
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
                    <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'py-12 text-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'py-12 text-center']); ?>
                        <p class="text-sm text-muted-foreground">No edits waiting for approval.</p>
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
            <?php else: ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $editRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editRequest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                            <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'p-4 space-y-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-4 space-y-3']); ?>
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <?php if (isset($component)) { $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.avatar','data' => ['name' => $editRequest->contact->name,'size' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($editRequest->contact->name),'size' => 'md']); ?>
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
                                        <div>
                                            <a href="<?php echo e(route('contacts.show', $editRequest->contact)); ?>" class="font-semibold text-base hover:underline"><?php echo e($editRequest->contact->name); ?></a>
                                            <div class="text-xs text-muted-foreground">
                                                Proposed by <span class="font-medium text-foreground"><?php echo e($editRequest->requestedBy?->name ?? '—'); ?></span>
                                                · <?php echo e($editRequest->created_at->diffForHumans()); ?>

                                            </div>
                                        </div>
                                    </div>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve-edits')): ?>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <form method="POST" action="<?php echo e(route('contact-edits.approve', $editRequest)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="inline-flex h-8 items-center gap-1 rounded-md bg-green-600 px-3 text-xs font-medium text-white hover:bg-green-700 transition-colors">
                                                    ✓ Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo e(route('contact-edits.reject', $editRequest)); ?>" onsubmit="return confirm('Reject this edit?')">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="inline-flex h-8 items-center gap-1 rounded-md bg-red-600 px-3 text-xs font-medium text-white hover:bg-red-700 transition-colors">
                                                    ✕ Reject
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="rounded-md border border-input divide-y text-sm">
                                    <?php $__currentLoopData = $editRequest->changes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $newValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 p-2">
                                            <div class="text-xs font-medium text-muted-foreground uppercase tracking-wide"><?php echo e(str_replace('_', ' ', $field)); ?></div>
                                            <div class="text-muted-foreground line-through decoration-destructive/60"><?php echo e(\Illuminate\Support\Str::limit((string) ($editRequest->original[$field] ?? ''), 80) ?: '(empty)'); ?></div>
                                            <div class="font-medium"><?php echo e(\Illuminate\Support\Str::limit((string) $newValue, 80) ?: '(empty)'); ?></div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($editRequest->tags !== null): ?>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 p-2">
                                            <div class="text-xs font-medium text-muted-foreground uppercase tracking-wide">Tags</div>
                                            <div class="sm:col-span-2 text-muted-foreground">Will be set to: <?php echo e(\App\Models\Tag::whereIn('id', $editRequest->tags)->pluck('name')->implode(', ') ?: '(none)'); ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($editRequest->photo_path): ?>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 p-2">
                                            <div class="text-xs font-medium text-muted-foreground uppercase tracking-wide">Photo</div>
                                            <div class="sm:col-span-2 text-muted-foreground">A new profile photo was uploaded.</div>
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if($editRequests->hasPages()): ?>
                    <div><?php echo e($editRequests->links()); ?></div>
                <?php endif; ?>
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
<?php /**PATH D:\xampp_lite\xampp_lite_8_3\www\laracontact\resources\views/contacts/pending.blade.php ENDPATH**/ ?>