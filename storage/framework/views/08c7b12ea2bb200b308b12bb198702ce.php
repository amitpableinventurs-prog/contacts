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
     <?php $__env->slot('header', null, []); ?> Contacts / <?php echo e($contact->name); ?> <?php $__env->endSlot(); ?>

    <div class="space-y-4">

        <?php if(($duplicateCount ?? 0) > 0): ?>
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'border-warning/40 bg-warning/5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'border-warning/40 bg-warning/5']); ?>
                <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'p-4 flex items-center gap-3 text-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-4 flex items-center gap-3 text-sm']); ?>
                    <svg class="h-4 w-4 shrink-0 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                    <div class="flex-1">
                        <span class="font-medium"><?php echo e($duplicateCount); ?> potential <?php echo e(\Illuminate\Support\Str::plural('duplicate', $duplicateCount)); ?></span>
                        <span class="text-muted-foreground">— matching email or phone.</span>
                    </div>
                    <a href="<?php echo e(route('contacts.merge', $contact)); ?>"><?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['size' => 'sm','variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','variant' => 'outline']); ?>Review &amp; merge <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?></a>
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
        <?php endif; ?>

        
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                <a href="<?php echo e(route('contacts.index')); ?>" class="hover:text-foreground">Contacts</a>
                <span>/</span>
                <span class="text-foreground"><?php echo e($contact->name); ?></span>
            </div>
            <div class="flex items-center gap-2">
                <?php if(auth()->user()->isClerk()): ?>
                    <?php
                        $telNumber = $contact->phone ?: $contact->number;
                        $waDigits  = $contact->phone_digits ?: preg_replace('/\D+/', '', (string) $telNumber);
                        if (strlen($waDigits) === 10) {
                            $waDigits = '91'.$waDigits;
                        }
                    ?>
                    <?php if($telNumber): ?>
                        <a href="tel:<?php echo e($telNumber); ?>" title="Call"
                           class="rounded-md border border-input bg-background p-2 hover:bg-accent text-muted-foreground hover:text-foreground">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if($waDigits): ?>
                        <a href="https://wa.me/<?php echo e($waDigits); ?>" target="_blank" rel="noopener" title="WhatsApp"
                           class="rounded-md border border-input bg-background p-2 hover:bg-accent text-green-600 hover:text-green-700">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if($contact->email): ?>
                        <a href="mailto:<?php echo e($contact->email); ?>" title="Email"
                           class="rounded-md border border-input bg-background p-2 hover:bg-accent text-muted-foreground hover:text-foreground">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(($contact->phone ?: $contact->number) && !auth()->user()->isClerk()): ?>
                    <form method="POST" action="<?php echo e(route('calls.log')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="contact_id" value="<?php echo e($contact->id); ?>" />
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'outline','size' => 'sm','type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','size' => 'sm','type' => 'submit']); ?>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Call
                         <?php echo $__env->renderComponent(); ?>
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
                    <a href="<?php echo e(route('sms.show', $contact)); ?>">
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'outline','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','size' => 'sm']); ?>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            SMS
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $contact)): ?>
                    <a href="<?php echo e(route('contacts.edit', $contact)); ?>"><?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm']); ?>Edit <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?></a>
                <?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.dropdown-menu','data' => ['align' => 'end']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'end']); ?>
                     <?php $__env->slot('trigger', null, []); ?> 
                        <button class="rounded-md border border-input bg-background h-9 px-3 hover:bg-accent">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
                        </button>
                     <?php $__env->endSlot(); ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $contact)): ?>
                        <?php if($contact->status !== 'suspended'): ?>
                            <form method="POST" action="<?php echo e(route('contacts.suspend', $contact)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.dropdown-menu-item','data' => ['as' => 'button','type' => 'submit','class' => 'text-orange-600']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['as' => 'button','type' => 'submit','class' => 'text-orange-600']); ?>Suspend <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                            </form>
                        <?php endif; ?>
                        <?php if($contact->status !== 'banned'): ?>
                            <form method="POST" action="<?php echo e(route('contacts.ban', $contact)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.dropdown-menu-item','data' => ['as' => 'button','type' => 'submit','class' => 'text-red-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['as' => 'button','type' => 'submit','class' => 'text-red-700']); ?>Ban <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                            </form>
                        <?php endif; ?>
                        <?php if($contact->status !== 'active'): ?>
                            <form method="POST" action="<?php echo e(route('contacts.reactivate', $contact)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.dropdown-menu-item','data' => ['as' => 'button','type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['as' => 'button','type' => 'submit']); ?>Reactivate <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                            </form>
                        <?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalf65d96b0176bd59b5a21f3499c9aac29 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf65d96b0176bd59b5a21f3499c9aac29 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.dropdown-menu-separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf65d96b0176bd59b5a21f3499c9aac29)): ?>
<?php $attributes = $__attributesOriginalf65d96b0176bd59b5a21f3499c9aac29; ?>
<?php unset($__attributesOriginalf65d96b0176bd59b5a21f3499c9aac29); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf65d96b0176bd59b5a21f3499c9aac29)): ?>
<?php $component = $__componentOriginalf65d96b0176bd59b5a21f3499c9aac29; ?>
<?php unset($__componentOriginalf65d96b0176bd59b5a21f3499c9aac29); ?>
<?php endif; ?>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $contact)): ?>
                        <form method="POST" action="<?php echo e(route('contacts.destroy', $contact)); ?>" onsubmit="return confirm('Move <?php echo e(addslashes($contact->name)); ?> to trash?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.dropdown-menu-item','data' => ['as' => 'button','type' => 'submit','destructive' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['as' => 'button','type' => 'submit','destructive' => true]); ?>🗑 Move to trash <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        </form>
                    <?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $attributes = $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $component = $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'lg:col-span-1 h-fit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'lg:col-span-1 h-fit']); ?>
                <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'p-6 space-y-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-6 space-y-4']); ?>
                    <div class="flex flex-col items-center text-center">
                        <?php if (isset($component)) { $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.avatar','data' => ['name' => $contact->name,'src' => $contact->photo ? asset('storage/'.$contact->photo) : null,'size' => 'xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($contact->name),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($contact->photo ? asset('storage/'.$contact->photo) : null),'size' => 'xl']); ?>
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
                        <h2 class="mt-3 text-lg font-semibold"><?php echo e($contact->name); ?></h2>
                        <?php if($contact->job_title || $contact->company): ?>
                            <p class="text-sm text-muted-foreground"><?php echo e(collect([$contact->job_title, $contact->company])->filter()->join(' · ')); ?></p>
                        <?php endif; ?>

                        
                        <?php if($contact->status === 'suspended'): ?>
                            <span class="mt-1 inline-flex items-center rounded-md bg-orange-100 px-2 py-0.5 text-xs font-medium text-orange-800">Suspended</span>
                        <?php elseif($contact->status === 'banned'): ?>
                            <span class="mt-1 inline-flex items-center rounded-md bg-red-700 px-2 py-0.5 text-xs font-semibold text-white">Banned</span>
                        <?php endif; ?>

                        <div class="mt-2 flex flex-wrap items-center justify-center gap-1.5">
                            <?php if($contact->lifecycle_stage): ?>
                                <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary']); ?><?php echo e(ucfirst($contact->lifecycle_stage)); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                            <?php endif; ?>
                            <?php if($contact->group): ?>
                                <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline']); ?>
                                    <span class="h-1.5 w-1.5 rounded-full mr-1" style="background:<?php echo e($contact->group->color ?: '#a855f7'); ?>"></span>
                                    <?php echo e($contact->group->name); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="text-center">
                        <?php $r = (float)($contact->rating ?? 0); ?>
                        <div class="flex items-center justify-center gap-0.5 mb-1">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <svg class="h-5 w-5 <?php echo e($i <= $r ? 'text-yellow-400 fill-yellow-400' : 'text-muted-foreground fill-transparent'); ?>" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <p class="text-xs text-muted-foreground"><?php echo e($r > 0 ? number_format($r, 1).'/5' : 'Not rated'); ?></p>
                    </div>

                    <?php if (isset($component)) { $__componentOriginal91da587e3ed37d9e80f8e31879836b4f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91da587e3ed37d9e80f8e31879836b4f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91da587e3ed37d9e80f8e31879836b4f)): ?>
<?php $attributes = $__attributesOriginal91da587e3ed37d9e80f8e31879836b4f; ?>
<?php unset($__attributesOriginal91da587e3ed37d9e80f8e31879836b4f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91da587e3ed37d9e80f8e31879836b4f)): ?>
<?php $component = $__componentOriginal91da587e3ed37d9e80f8e31879836b4f; ?>
<?php unset($__componentOriginal91da587e3ed37d9e80f8e31879836b4f); ?>
<?php endif; ?>

                    <dl class="space-y-3 text-sm">
                        <?php if($contact->email): ?>
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Email</dt>
                            <dd><a href="mailto:<?php echo e($contact->email); ?>" class="hover:underline"><?php echo e($contact->email); ?></a></dd></div>
                        <?php endif; ?>
                        <?php $phone = $contact->phone ?: $contact->number; ?>
                        <?php if($phone): ?>
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Phone</dt><dd><?php echo e($phone); ?></dd></div>
                        <?php endif; ?>
                        <?php if($contact->admin_comment): ?>
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Comment</dt>
                            <dd class="mt-1 rounded-md border border-yellow-300 bg-yellow-50 px-2 py-1.5 text-yellow-800 whitespace-pre-line"><?php echo e($contact->admin_comment); ?></dd></div>
                        <?php endif; ?>
                        <?php if($contact->website): ?>
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Website</dt>
                            <dd><a href="<?php echo e($contact->website); ?>" target="_blank" rel="noopener" class="hover:underline truncate block"><?php echo e($contact->website); ?></a></dd></div>
                        <?php endif; ?>
                        <?php if($contact->address): ?>
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Address</dt><dd><?php echo e($contact->address); ?></dd></div>
                        <?php endif; ?>
                        <?php if($contact->city): ?>
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">City</dt><dd><?php echo e($contact->city); ?></dd></div>
                        <?php endif; ?>
                        <?php if($contact->birthday): ?>
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Birthday</dt><dd><?php echo e($contact->birthday->format('M j, Y')); ?></dd></div>
                        <?php endif; ?>
                        <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Last contacted</dt><dd><?php echo e($contact->last_contacted_at?->diffForHumans() ?? '—'); ?></dd></div>
                        <?php if($contact->owner): ?>
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Owner</dt><dd><?php echo e($contact->owner->name); ?></dd></div>
                        <?php endif; ?>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-muted-foreground">Created</dt>
                            <dd title="<?php echo e($contact->created_at->format('d M Y H:i:s')); ?>"><?php echo e($contact->created_at->format('d M Y, h:i A')); ?></dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-muted-foreground">Last Updated</dt>
                            <dd title="<?php echo e($contact->updated_at->format('d M Y H:i:s')); ?>"><?php echo e($contact->updated_at->diffForHumans()); ?> <span class="text-muted-foreground text-xs">(<?php echo e($contact->updated_at->format('d M Y')); ?>)</span></dd>
                        </div>
                    </dl>

                    <?php if($contact->tags->isNotEmpty()): ?>
                        <?php if (isset($component)) { $__componentOriginal91da587e3ed37d9e80f8e31879836b4f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91da587e3ed37d9e80f8e31879836b4f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91da587e3ed37d9e80f8e31879836b4f)): ?>
<?php $attributes = $__attributesOriginal91da587e3ed37d9e80f8e31879836b4f; ?>
<?php unset($__attributesOriginal91da587e3ed37d9e80f8e31879836b4f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91da587e3ed37d9e80f8e31879836b4f)): ?>
<?php $component = $__componentOriginal91da587e3ed37d9e80f8e31879836b4f; ?>
<?php unset($__componentOriginal91da587e3ed37d9e80f8e31879836b4f); ?>
<?php endif; ?>
                        <div>
                            <div class="text-xs uppercase tracking-wide text-muted-foreground mb-2">Tags</div>
                            <div class="flex flex-wrap gap-1.5">
                                <?php $__currentLoopData = $contact->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline']); ?><?php echo e($tag->name); ?> <?php echo $__env->renderComponent(); ?>
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
                    <?php endif; ?>

                    <?php if($contact->facebook || $contact->twitter || $contact->linkedin): ?>
                        <?php if (isset($component)) { $__componentOriginal91da587e3ed37d9e80f8e31879836b4f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91da587e3ed37d9e80f8e31879836b4f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91da587e3ed37d9e80f8e31879836b4f)): ?>
<?php $attributes = $__attributesOriginal91da587e3ed37d9e80f8e31879836b4f; ?>
<?php unset($__attributesOriginal91da587e3ed37d9e80f8e31879836b4f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91da587e3ed37d9e80f8e31879836b4f)): ?>
<?php $component = $__componentOriginal91da587e3ed37d9e80f8e31879836b4f; ?>
<?php unset($__componentOriginal91da587e3ed37d9e80f8e31879836b4f); ?>
<?php endif; ?>
                        <div>
                            <div class="text-xs uppercase tracking-wide text-muted-foreground mb-2">Social</div>
                            <div class="space-y-1 text-sm">
                                <?php if($contact->twitter): ?>  <div>🐦 <?php echo e($contact->twitter); ?></div> <?php endif; ?>
                                <?php if($contact->linkedin): ?> <div>💼 <?php echo e($contact->linkedin); ?></div> <?php endif; ?>
                                <?php if($contact->facebook): ?> <div>📘 <?php echo e($contact->facebook); ?></div> <?php endif; ?>
                            </div>
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

            
            <div class="lg:col-span-2">
                <?php if (isset($component)) { $__componentOriginal74888cb3b248a08ce228c04e2cfe93a9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal74888cb3b248a08ce228c04e2cfe93a9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs','data' => ['default' => 'activity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['default' => 'activity']); ?>
                    <?php if (isset($component)) { $__componentOriginal73edf79101629ccfea8f5195e64e5c93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal73edf79101629ccfea8f5195e64e5c93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-list','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-trigger','data' => ['value' => 'activity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-trigger'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'activity']); ?>Activity <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $attributes = $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $component = $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-trigger','data' => ['value' => 'notes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-trigger'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'notes']); ?>Notes (<?php echo e($contact->contactNotes->count()); ?>) <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $attributes = $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $component = $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-trigger','data' => ['value' => 'history']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-trigger'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'history']); ?>History (<?php echo e($contact->editHistories->count()); ?>) <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $attributes = $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $component = $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-trigger','data' => ['value' => 'description']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-trigger'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'description']); ?>Description <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $attributes = $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $component = $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-trigger','data' => ['value' => 'files']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-trigger'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'files']); ?>Files (<?php echo e($contact->files->count()); ?>) <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $attributes = $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $component = $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-trigger','data' => ['value' => 'gallery']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-trigger'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'gallery']); ?>Gallery (<?php echo e($contact->galleryImages->count()); ?>) <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $attributes = $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $component = $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-trigger','data' => ['value' => 'custom']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-trigger'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'custom']); ?>Custom fields <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $attributes = $__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__attributesOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8)): ?>
<?php $component = $__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8; ?>
<?php unset($__componentOriginal5c13e0c5e6a6da1413ef4270da8ff6b8); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal73edf79101629ccfea8f5195e64e5c93)): ?>
<?php $attributes = $__attributesOriginal73edf79101629ccfea8f5195e64e5c93; ?>
<?php unset($__attributesOriginal73edf79101629ccfea8f5195e64e5c93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal73edf79101629ccfea8f5195e64e5c93)): ?>
<?php $component = $__componentOriginal73edf79101629ccfea8f5195e64e5c93; ?>
<?php unset($__componentOriginal73edf79101629ccfea8f5195e64e5c93); ?>
<?php endif; ?>

                    
                    <?php if (isset($component)) { $__componentOriginalcc983c56435e86cea45f842c6b082e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcc983c56435e86cea45f842c6b082e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-content','data' => ['value' => 'activity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'activity']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'p-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-0']); ?>
                                <?php $combined = $activity->concat($emails)->sortByDesc(fn ($i) => $i->sent_at ?? $i->created_at); ?>
                                <?php if($combined->isEmpty()): ?>
                                    <p class="text-sm text-muted-foreground py-12 text-center">No activity yet.</p>
                                <?php else: ?>
                                    <ul class="divide-y">
                                        <?php $__currentLoopData = $combined; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $isMessage = $item instanceof \App\Models\Message;
                                                $type = $isMessage ? $item->channel : 'email';
                                                $verb = match(true) {
                                                    $type === 'sms' && ($item->direction ?? '') === 'outbound' => 'Sent SMS',
                                                    $type === 'sms' => 'Received SMS',
                                                    $type === 'voice' => 'Called',
                                                    default => 'Emailed',
                                                };
                                                $preview = $isMessage ? $item->body : $item->subject;
                                                $when = $item->sent_at ?? $item->created_at;
                                            ?>
                                            <li class="flex items-start gap-3 p-4">
                                                <div class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-muted text-xs font-medium">
                                                    <?php echo e($type === 'sms' ? 'SMS' : ($type === 'voice' ? '☎' : '✉')); ?>

                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-baseline justify-between gap-3">
                                                        <p class="text-sm font-medium"><?php echo e($verb); ?></p>
                                                        <span class="text-xs text-muted-foreground shrink-0"><?php echo e($when?->diffForHumans()); ?></span>
                                                    </div>
                                                    <?php if($preview): ?>
                                                        <p class="text-sm text-muted-foreground truncate"><?php echo e($preview); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
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
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $attributes = $__attributesOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__attributesOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $component = $__componentOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__componentOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>

                    
                    <?php if (isset($component)) { $__componentOriginalcc983c56435e86cea45f842c6b082e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcc983c56435e86cea45f842c6b082e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-content','data' => ['value' => 'history']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'history']); ?>
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
<?php $component->withAttributes([]); ?>Edit History <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Last 5 changes to this contact. <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'p-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-0']); ?>
                                <?php if($contact->editHistories->isEmpty()): ?>
                                    <p class="text-sm text-muted-foreground py-10 text-center">No edits recorded yet.</p>
                                <?php else: ?>
                                    <ul class="divide-y">
                                        <?php $__currentLoopData = $contact->editHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="p-4 space-y-2">
                                                <div class="flex items-center justify-between text-xs text-muted-foreground">
                                                    <span class="font-medium text-foreground"><?php echo e($history->user?->name ?? 'Unknown user'); ?></span>
                                                    <span title="<?php echo e($history->created_at->format('d M Y H:i:s')); ?>"><?php echo e($history->created_at->format('d M Y, h:i A')); ?></span>
                                                </div>
                                                <ul class="space-y-1">
                                                    <?php $__currentLoopData = $history->changed_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="text-xs flex flex-wrap items-baseline gap-x-1.5">
                                                            <span class="font-semibold capitalize"><?php echo e(str_replace('_', ' ', $field)); ?>:</span>
                                                            <?php if($change['from']): ?>
                                                                <span class="text-red-600 line-through"><?php echo e($change['from']); ?></span>
                                                                <span class="text-muted-foreground">→</span>
                                                            <?php endif; ?>
                                                            <span class="text-green-700"><?php echo e($change['to'] ?? '(cleared)'); ?></span>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
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
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $attributes = $__attributesOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__attributesOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $component = $__componentOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__componentOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>

                    
                    <?php if (isset($component)) { $__componentOriginalcc983c56435e86cea45f842c6b082e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcc983c56435e86cea45f842c6b082e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-content','data' => ['value' => 'notes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'notes']); ?>
                        <div class="space-y-3">
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('addNote', $contact)): ?>
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
<?php $component->withAttributes([]); ?><?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Add note <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?> <?php echo $__env->renderComponent(); ?>
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
                                    <form method="POST" action="<?php echo e(route('contacts.notes.store', $contact)); ?>" class="space-y-3">
                                        <?php echo csrf_field(); ?>
                                        <?php if (isset($component)) { $__componentOriginal62d1193389a71cd99ff302a00abbf991 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal62d1193389a71cd99ff302a00abbf991 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.textarea','data' => ['name' => 'note_html','rows' => '3','placeholder' => 'Add a note about this contact...','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'note_html','rows' => '3','placeholder' => 'Add a note about this contact...','required' => true]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $attributes = $__attributesOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__attributesOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $component = $__componentOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__componentOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
                                        <?php $__errorArgs = ['note_html'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-destructive"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','size' => 'sm']); ?>Save note <?php echo $__env->renderComponent(); ?>
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
                            <?php endif; ?>

                            
                            <?php if($contact->getAttributes()['notes'] ?? null): ?>
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
<?php $component->withAttributes([]); ?><?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => ['class' => 'text-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-sm']); ?>Quick notes <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?> <?php echo $__env->renderComponent(); ?>
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
                                        <p class="text-sm whitespace-pre-line leading-relaxed"><?php echo e($contact->getAttributes()['notes']); ?></p>
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
                            <?php endif; ?>

                            
                            <?php $__empty_1 = true; $__currentLoopData = $contact->contactNotes->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex items-center gap-2 text-xs text-muted-foreground mb-2">
                                                <?php if (isset($component)) { $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.avatar','data' => ['name' => $note->author?->name ?? 'User','size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($note->author?->name ?? 'User'),'size' => 'xs']); ?>
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
                                                <span><?php echo e($note->author?->name ?? 'Unknown'); ?></span>
                                                <span>·</span>
                                                <span><?php echo e($note->created_at->diffForHumans()); ?></span>
                                            </div>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $contact)): ?>
                                                <form method="POST" action="<?php echo e(route('contacts.notes.destroy', [$contact, $note])); ?>">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-muted-foreground hover:text-destructive" title="Delete note">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-sm whitespace-pre-line"><?php echo nl2br(e($note->note_html)); ?></p>
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-sm text-muted-foreground text-center py-8">No notes yet.</p>
                            <?php endif; ?>
                        </div>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $attributes = $__attributesOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__attributesOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $component = $__componentOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__componentOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>

                    
                    <?php if (isset($component)) { $__componentOriginalcc983c56435e86cea45f842c6b082e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcc983c56435e86cea45f842c6b082e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-content','data' => ['value' => 'description']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'description']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'p-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-6']); ?>
                                <?php if($contact->description_html): ?>
                                    <div class="prose prose-sm max-w-none"><?php echo $contact->description_html; ?></div>
                                <?php else: ?>
                                    <p class="text-sm text-muted-foreground italic">No description added. Use the Edit form to add one.</p>
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
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $attributes = $__attributesOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__attributesOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $component = $__componentOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__componentOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>

                    
                    <?php if (isset($component)) { $__componentOriginalcc983c56435e86cea45f842c6b082e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcc983c56435e86cea45f842c6b082e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-content','data' => ['value' => 'files']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'files']); ?>
                        <div class="space-y-3">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $contact)): ?>
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
<?php $component->withAttributes([]); ?><?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Upload file <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?> <?php echo $__env->renderComponent(); ?>
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
                                        <form method="POST" action="<?php echo e(route('contacts.files.store', $contact)); ?>" enctype="multipart/form-data" class="flex flex-wrap items-end gap-3">
                                            <?php echo csrf_field(); ?>
                                            <div class="flex-1 space-y-1.5">
                                                <input type="file" name="file" required class="block w-full text-sm text-muted-foreground file:mr-3 file:rounded-md file:border file:border-input file:bg-background file:px-3 file:py-1 file:text-sm file:font-medium file:cursor-pointer hover:file:bg-accent" />
                                                <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-destructive"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','size' => 'sm']); ?>Upload <?php echo $__env->renderComponent(); ?>
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
                            <?php endif; ?>

                            <?php $__empty_1 = true; $__currentLoopData = $contact->files->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'p-4 flex items-center gap-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-4 flex items-center gap-3']); ?>
                                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-muted">
                                            <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium truncate"><?php echo e($file->file_name); ?></p>
                                            <p class="text-xs text-muted-foreground"><?php echo e(number_format($file->size_bytes / 1024, 1)); ?> KB · <?php echo e($file->created_at->diffForHumans()); ?></p>
                                        </div>
                                        <a href="<?php echo e(asset('storage/'.$file->file_path)); ?>" download="<?php echo e($file->file_name); ?>"
                                           class="text-xs text-muted-foreground hover:text-foreground px-2 py-1 rounded border border-input hover:bg-accent">Download</a>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $contact)): ?>
                                            <form method="POST" action="<?php echo e(route('contacts.files.destroy', [$contact, $file])); ?>" onsubmit="return confirm('Delete file?')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-muted-foreground hover:text-destructive">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-sm text-muted-foreground text-center py-8">No files uploaded.</p>
                            <?php endif; ?>
                        </div>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $attributes = $__attributesOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__attributesOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $component = $__componentOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__componentOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>

                    
                    <?php if (isset($component)) { $__componentOriginalcc983c56435e86cea45f842c6b082e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcc983c56435e86cea45f842c6b082e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-content','data' => ['value' => 'gallery']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'gallery']); ?>
                        <div class="space-y-3">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $contact)): ?>
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
<?php $component->withAttributes([]); ?><?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Upload images <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?> <?php echo $__env->renderComponent(); ?>
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
                                        <form method="POST" action="<?php echo e(route('contacts.gallery.store', $contact)); ?>" enctype="multipart/form-data" class="flex flex-wrap items-end gap-3">
                                            <?php echo csrf_field(); ?>
                                            <div class="flex-1 space-y-1.5">
                                                <input type="file" name="images[]" multiple accept="image/*" required class="block w-full text-sm text-muted-foreground file:mr-3 file:rounded-md file:border file:border-input file:bg-background file:px-3 file:py-1 file:text-sm file:font-medium file:cursor-pointer hover:file:bg-accent" />
                                                <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-destructive"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','size' => 'sm']); ?>Upload <?php echo $__env->renderComponent(); ?>
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
                            <?php endif; ?>

                            <?php if($contact->galleryImages->isNotEmpty()): ?>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <?php $__currentLoopData = $contact->galleryImages->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="relative group rounded-lg overflow-hidden border border-input bg-muted aspect-square">
                                            <img src="<?php echo e(asset('storage/'.$image->image_path)); ?>" alt="<?php echo e($image->image_name); ?>" class="w-full h-full object-cover" />
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $contact)): ?>
                                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <form method="POST" action="<?php echo e(route('contacts.gallery.destroy', [$contact, $image])); ?>" onsubmit="return confirm('Delete image?')">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="bg-destructive text-destructive-foreground rounded-md px-3 py-1.5 text-xs font-medium">Delete</button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-muted-foreground text-center py-8">No images in gallery.</p>
                            <?php endif; ?>
                        </div>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $attributes = $__attributesOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__attributesOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $component = $__componentOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__componentOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>

                    
                    <?php if (isset($component)) { $__componentOriginalcc983c56435e86cea45f842c6b082e48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcc983c56435e86cea45f842c6b082e48 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.tabs-content','data' => ['value' => 'custom']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tabs-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'custom']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'p-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-6']); ?>
                                <?php if(!empty($contact->custom_fields)): ?>
                                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                        <?php $__currentLoopData = $contact->custom_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div>
                                                <dt class="text-xs uppercase tracking-wide text-muted-foreground"><?php echo e($key); ?></dt>
                                                <dd><?php echo e(is_scalar($value) ? $value : json_encode($value)); ?></dd>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </dl>
                                <?php else: ?>
                                    <p class="text-sm text-muted-foreground italic">No custom fields set.</p>
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
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $attributes = $__attributesOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__attributesOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcc983c56435e86cea45f842c6b082e48)): ?>
<?php $component = $__componentOriginalcc983c56435e86cea45f842c6b082e48; ?>
<?php unset($__componentOriginalcc983c56435e86cea45f842c6b082e48); ?>
<?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal74888cb3b248a08ce228c04e2cfe93a9)): ?>
<?php $attributes = $__attributesOriginal74888cb3b248a08ce228c04e2cfe93a9; ?>
<?php unset($__attributesOriginal74888cb3b248a08ce228c04e2cfe93a9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal74888cb3b248a08ce228c04e2cfe93a9)): ?>
<?php $component = $__componentOriginal74888cb3b248a08ce228c04e2cfe93a9; ?>
<?php unset($__componentOriginal74888cb3b248a08ce228c04e2cfe93a9); ?>
<?php endif; ?>
            </div>
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
<?php /**PATH D:\xampp_lite\xampp_lite_8_3\www\laracontact\resources\views/contacts/show.blade.php ENDPATH**/ ?>