<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';  // ← 追加

const count = ref(0);  // ← 追加
</script>

<template>
    <Head title="ふたり家計簿" />
    
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-md mx-auto p-6 pt-20">
            <h1 class="text-3xl font-bold text-center text-blue-600 mb-8">
                ふたり家計簿
            </h1>
            
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">🎉 環境構築完了！</h2>
                    <p class="text-sm text-gray-600">
                        Laravel + Inertia.js + Vue 3 + Alpine.js + DaisyUI
                    </p>
                    
                    <div class="divider"></div>
                    
                    <!-- DaisyUI のテスト -->
                    <div class="space-y-3">
                        <button class="btn btn-primary w-full">
                            DaisyUI ボタン
                        </button>
                        
                        <div class="alert alert-success">
                            <span>✅ DaisyUI が動作しています</span>
                        </div>
                    </div>
                    
                    <!-- Vue のテスト（Alpine.js の代わり） -->
                    <div class="divider"></div>
                    
                    <div class="space-y-2">
                        <p class="text-sm font-medium">Vue テスト:</p>
                        <button 
                            @click="count++" 
                            class="btn btn-secondary w-full"
                        >
                            クリック数: {{ count }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>