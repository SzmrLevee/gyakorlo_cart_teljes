import { api } from '@utils/http.mjs';
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useProducts = defineStore('products', () => {
    const products = ref([]);
    const isLoading = ref(true);

    async function load() {
        const res = await api.get("products");
        products.value = res.data.data;
        isLoading.value = false;
    }
    load();

    return {products, isLoading};
});