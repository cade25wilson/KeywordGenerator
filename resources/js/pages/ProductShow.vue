<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage();
const name = ref('');
const currentSlide = ref(0);
const onNext = () => {
  currentSlide.value = (currentSlide.value + 1) % 5;
};
const onPrev = () => {
  currentSlide.value = (currentSlide.value + 4) % 5;
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
    {{ page.props.product }}
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <Carousel class="w-full max-w-xs">
                    <CarouselContent>
                        <CarouselItem v-for="(item, index) in 5" :key="index" v-show="index === currentSlide">
                            <div class="p-1">
                                <Card>
                                    <CardContent class="flex aspect-square items-center justify-center p-6">
                                        <span class="text-4xl font-semibold">{{ index + 1 }}</span>
                                    </CardContent>
                                </Card>
                            </div>
                        </CarouselItem>
                    </CarouselContent>
                    <CarouselPrevious @next="onPrev" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white p-2 rounded shadow" />
                    <CarouselNext @next="onNext" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white p-2 rounded shadow" />
                </Carousel>
            </div>
            
        </div>
    </AppLayout>
</template>
