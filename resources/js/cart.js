document.addEventListener('alpine:init', () => {
    Alpine.data('cartComponent', (initialItems, config) => ({
        cartItems: initialItems,
        
        formatCurrency(amount) {
            return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(amount);
        },

        async updateQuantity(id, delta) {
            let item = this.cartItems.find(i => i.id === id);
            if (item) {
                const newQty = Math.max(1, item.quantity + delta);
                item.quantity = newQty;
                
                try {
                    await fetch(config.updateUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": config.csrfToken
                        },
                        body: JSON.stringify({ id: id, quantity: newQty })
                    });
                } catch (error) {
                    console.error("Lỗi cập nhật giỏ hàng:", error);
                }
            }
        },

        async removeItem(id) {
            if (confirm("Xác nhận xóa sản phẩm này khỏi giỏ hàng?")) {
                this.cartItems = this.cartItems.filter(i => i.id !== id);
                
                try {
                    await fetch(config.removeUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": config.csrfToken
                        },
                        body: JSON.stringify({ id: id })
                    });
                } catch (error) {
                    console.error("Lỗi xóa sản phẩm:", error);
                }
            }
        },

        get subtotal() {
            return this.cartItems.reduce((acc, item) => acc + item.price * item.quantity, 0);
        },

        get total() {
            return this.subtotal;
        }
    }));
});
