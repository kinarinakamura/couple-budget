<script setup>
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    expenses: Number,
    method: String,
    ratioA: Number,
    ratioB: Number,
    splitA: Number,
    splitB: Number,
});

// アドバイスを生成
const advice = computed(() => {
    if (props.method === 'equal') {
        return '均等に分担で、シンプルで分かりやすいですね！';
    }
    return 'この比率で無理なく分担できそうですね！';
});
</script>

<template>
    <Head title="診断結果 - ふたり家計簿" />
    
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-md mx-auto p-6">
            <div class="text-center mb-8 mt-8">
                <h1 class="text-2xl font-bold text-blue-600 mb-2 font-display">
                    診断結果
                </h1>
                <p class="text-xs text-gray-500">共有された診断結果</p>
            </div>

            <div class="card bg-base-100 shadow-xl mb-4">
                <div class="card-body">
                    <h2 class="card-title justify-center">
                        おすすめの分担方法
                    </h2>
                    
                    <!-- 分担比率 -->
                    <div class="text-center my-6">
                        <div class="text-sm text-gray-600 mb-2">分担比率</div>
                        <div class="text-5xl font-bold text-primary">
                            {{ ratioA }}:{{ ratioB }}
                        </div>
                    </div>
                    
                    <!-- 月の生活費 -->
                    <div class="text-center mb-4">
                        <div class="text-sm text-gray-600">月の生活費</div>
                        <div class="text-2xl font-bold">
                            {{ expenses }}万円
                        </div>
                    </div>

                    <div class="divider"></div>
                    
                    <!-- 各自の負担額 -->
                    <div class="grid grid-cols-2 gap-4 my-4">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-sm text-gray-600 mb-1">Aさん</div>
                            <div class="text-2xl font-bold text-blue-600">
                                {{ splitA }}
                            </div>
                            <div class="text-xs text-gray-500">万円/月</div>
                        </div>
                        
                        <div class="bg-pink-50 rounded-lg p-4 text-center">
                            <div class="text-sm text-gray-600 mb-1">Bさん</div>
                            <div class="text-2xl font-bold text-pink-600">
                                {{ splitB }}
                            </div>
                            <div class="text-xs text-gray-500">万円/月</div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    
                    <!-- アドバイス -->
                    <div class="alert alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ advice }}</span>
                    </div>
                    
                    <!-- アクションボタン -->
                    <div class="card-actions justify-center mt-6">
                        <a 
                            href="/"
                            class="btn btn-primary"
                        >
                            自分も診断してみる
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>