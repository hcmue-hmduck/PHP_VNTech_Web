function initCart() {
    Alpine.data('cartComponent', (initialItems, config) => ({
        cartItems: initialItems,
        updateUrl: config.updateUrl,
        removeUrl: config.removeUrl,
        csrfToken: config.csrfToken,

        formatCurrency(amount) {
            return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(amount);
        },

        get selectAllChecked() {
            return this.cartItems.length > 0 && this.cartItems.every(i => i.checked);
        },

        toggleAll() {
            const nextState = !this.selectAllChecked;
            this.cartItems.forEach(i => i.checked = nextState);
        },

        get selectedItems() {
            return this.cartItems.filter(i => i.checked);
        },

        get subtotal() {
            return this.selectedItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
        },

        get qualifiesForFreeShipping() {
            return this.subtotal >= 2000000;
        },

        get shippingFee() {
            if (this.subtotal === 0) return 0;
            return this.qualifiesForFreeShipping ? 0 : 30000;
        },

        get total() {
            return Math.max(0, this.subtotal + this.shippingFee);
        },

        async updateQuantity(id, delta) {
            let item = this.cartItems.find(i => i.id === id);
            if (item) {
                const newQty = Math.max(1, item.quantity + delta);
                item.quantity = newQty;
                
                try {
                    await fetch(this.updateUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": this.csrfToken
                        },
                        body: JSON.stringify({ id: id, quantity: newQty })
                    });
                } catch (error) {
                    console.error("Lỗi cập nhật số lượng:", error);
                }
            }
        },

        async removeItem(id) {
            if (confirm("Xác nhận xóa sản phẩm này khỏi giỏ hàng?")) {
                this.cartItems = this.cartItems.filter(i => i.id !== id);
                
                try {
                    await fetch(this.removeUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": this.csrfToken
                        },
                        body: JSON.stringify({ id: id })
                    });
                } catch (error) {
                    console.error("Lỗi xóa sản phẩm:", error);
                }
            }
        },

        async clearAll() {
            if (confirm("Xác nhận xóa toàn bộ sản phẩm khỏi giỏ hàng?")) {
                const itemsToClear = [...this.cartItems];
                this.cartItems = [];

                for (const item of itemsToClear) {
                    try {
                        await fetch(this.removeUrl, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": this.csrfToken
                            },
                            body: JSON.stringify({ id: item.id })
                        });
                    } catch (error) {
                        console.error("Lỗi xóa sản phẩm:", error);
                    }
                }
            }
        }
    }));
}

if (window.Alpine) {
    initCart();
} else {
    document.addEventListener('alpine:init', initCart);
}
