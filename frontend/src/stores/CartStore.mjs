import { defineStore } from "pinia";
import { ref } from "vue";

export const useCart = defineStore("cart", () => {
    const productsInCart = ref([]);
    function addToCart(product){
        productsInCart.value.push(product);
    }

    return{
        productsInCart,
        addToCart,
    };
}, {
    persist: true,
});