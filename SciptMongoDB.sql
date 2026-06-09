// VNTech E-Commerce Database Schema Script
// MongoDB Shell (mongosh) Compatible with JSON Schema Validation
// Generated to align strictly with Laravel Eloquent Models ($fillable, $casts, and property names)

// Select database
db = db.getSiblingDB('vntech');

// -------------------------------------------------------------
// 1. Collection: users (Model: User)
// -------------------------------------------------------------
db.createCollection('users', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_nguoi_dung", "ho_ten", "email", "vai_tro", "trang_thai"],
      properties: {
        ma_nguoi_dung: { bsonType: "string" },
        ho_ten: { bsonType: "string" },
        email: { bsonType: "string" },
        so_dien_thoai: { bsonType: ["string", "null"] },
        password: { bsonType: ["string", "null"] },
        vai_tro: { bsonType: "string" },
        avatar_url: { bsonType: ["string", "null"] },
        bio: { bsonType: ["string", "null"] },
        trang_thai: { bsonType: "string" },
        email_verified_at: { bsonType: ["date", "null"] },
        remember_token: { bsonType: ["string", "null"] },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.users.createIndex({ "ma_nguoi_dung": 1 }, { unique: true });
db.users.createIndex({ "email": 1 }, { unique: true });
db.users.createIndex({ "vai_tro": 1 });
db.users.createIndex({ "trang_thai": 1 });


// -------------------------------------------------------------
// 2. Collection: password_reset_tokens (Laravel Auth Token)
// -------------------------------------------------------------
db.createCollection('password_reset_tokens', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["email", "token"],
      properties: {
        email: { bsonType: "string" },
        token: { bsonType: "string" },
        created_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.password_reset_tokens.createIndex({ "email": 1 }, { unique: true });


// -------------------------------------------------------------
// 3. Collection: sessions (Laravel Session Driver)
// -------------------------------------------------------------
db.createCollection('sessions', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["id", "payload", "last_activity"],
      properties: {
        id: { bsonType: "string" },
        user_id: { bsonType: ["string", "null"] },
        ip_address: { bsonType: ["string", "null"] },
        user_agent: { bsonType: ["string", "null"] },
        payload: { bsonType: "string" },
        last_activity: { bsonType: "int" }
      }
    }
  }
});
db.sessions.createIndex({ "id": 1 }, { unique: true });
db.sessions.createIndex({ "user_id": 1 });
db.sessions.createIndex({ "last_activity": 1 });


// -------------------------------------------------------------
// 4. Collection: cache & cache_locks
// -------------------------------------------------------------
db.createCollection('cache', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["key", "value", "expiration"],
      properties: {
        key: { bsonType: "string" },
        value: { bsonType: "string" },
        expiration: { bsonType: "int" }
      }
    }
  }
});
db.cache.createIndex({ "key": 1 }, { unique: true });

db.createCollection('cache_locks', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["key", "owner", "expiration"],
      properties: {
        key: { bsonType: "string" },
        owner: { bsonType: "string" },
        expiration: { bsonType: "int" }
      }
    }
  }
});
db.cache_locks.createIndex({ "key": 1 }, { unique: true });


// -------------------------------------------------------------
// 5. Collection: jobs, failed_jobs, job_batches
// -------------------------------------------------------------
db.createCollection('jobs', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["queue", "payload", "attempts", "available_at", "created_at"],
      properties: {
        queue: { bsonType: "string" },
        payload: { bsonType: "string" },
        attempts: { bsonType: "int" },
        reserved_at: { bsonType: ["int", "null"] },
        available_at: { bsonType: "int" },
        created_at: { bsonType: "int" }
      }
    }
  }
});
db.jobs.createIndex({ "queue": 1 });

db.createCollection('failed_jobs', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["uuid", "connection", "queue", "payload", "exception"],
      properties: {
        uuid: { bsonType: "string" },
        connection: { bsonType: "string" },
        queue: { bsonType: "string" },
        payload: { bsonType: "string" },
        exception: { bsonType: "string" },
        failed_at: { bsonType: "date" }
      }
    }
  }
});
db.failed_jobs.createIndex({ "uuid": 1 }, { unique: true });

db.createCollection('job_batches', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["id", "name", "total_jobs", "pending_jobs", "failed_jobs", "failed_job_ids", "created_at"],
      properties: {
        id: { bsonType: "string" },
        name: { bsonType: "string" },
        total_jobs: { bsonType: "int" },
        pending_jobs: { bsonType: "int" },
        failed_jobs: { bsonType: "int" },
        failed_job_ids: { bsonType: "string" },
        options: { bsonType: ["string", "null"] },
        cancelled_at: { bsonType: ["int", "null"] },
        created_at: { bsonType: "int" },
        finished_at: { bsonType: ["int", "null"] }
      }
    }
  }
});
db.job_batches.createIndex({ "id": 1 }, { unique: true });


// -------------------------------------------------------------
// 6. Collection: brands (Model: Brand)
// -------------------------------------------------------------
db.createCollection('brands', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_thuong_hieu", "ten_thuong_hieu", "trang_thai"],
      properties: {
        ma_thuong_hieu: { bsonType: "string" },
        ten_thuong_hieu: { bsonType: "string" },
        mo_ta: { bsonType: ["string", "null"] },
        logo_url: { bsonType: ["string", "null"] },
        trang_thai: { bsonType: "string" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.brands.createIndex({ "ma_thuong_hieu": 1 }, { unique: true });
db.brands.createIndex({ "ten_thuong_hieu": 1 });


// -------------------------------------------------------------
// 7. Collection: categories (Model: Category)
// -------------------------------------------------------------
db.createCollection('categories', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_danh_muc", "ten_danh_muc", "trang_thai"],
      properties: {
        ma_danh_muc: { bsonType: "string" },
        ma_danh_muc_cha: { bsonType: ["string", "null"] },
        ten_danh_muc: { bsonType: "string" },
        logo_url: { bsonType: ["string", "null"] },
        trang_thai: { bsonType: "string" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.categories.createIndex({ "ma_danh_muc": 1 }, { unique: true });
db.categories.createIndex({ "ten_danh_muc": 1 });


// -------------------------------------------------------------
// 8. Collection: products (Model: Product)
// -------------------------------------------------------------
db.createCollection('products', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_san_pham", "ten_san_pham", "ma_danh_muc", "ma_thuong_hieu", "trang_thai", "kiem_tra_bien_the"],
      properties: {
        ma_san_pham: { bsonType: "string" },
        ten_san_pham: { bsonType: "string" },
        ma_danh_muc: { bsonType: "string" },
        ma_thuong_hieu: { bsonType: "string" },
        mo_ta_ngan: { bsonType: ["string", "null"] },
        mo_ta_chi_tiet: { bsonType: ["string", "null"] },
        link_anh_dai_dien: { bsonType: ["string", "null"] },
        trang_thai: { bsonType: "string" },
        hinh_anh: { bsonType: ["array", "null"], items: { bsonType: "string" } },
        thong_so_ky_thuat_chung: { bsonType: ["array", "null"] },
        thong_tin_them: { bsonType: ["array", "null"] },
        kiem_tra_bien_the: { bsonType: "bool" },
        luot_xem: { bsonType: "int" },
        gia_thap_nhat: { bsonType: "double" },
        so_sao_trung_binh: { bsonType: "double" },
        so_luot_danh_gia: { bsonType: "int" },
        tong_so_sao: { bsonType: "int" },
        tong_luot_ban: { bsonType: ["int", "null"] },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.products.createIndex({ "ma_san_pham": 1 }, { unique: true });
db.products.createIndex({ "ma_danh_muc": 1 });
db.products.createIndex({ "ma_thuong_hieu": 1 });


// -------------------------------------------------------------
// 9. Collection: product_variants (Model: ProductVariant)
// -------------------------------------------------------------
db.createCollection('product_variants', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_san_pham", "ma_bien_the", "ten_bien_the", "gia_ban", "so_luong_ton_kho", "thong_so_ky_thuat_rieng", "trang_thai"],
      properties: {
        ma_san_pham: { bsonType: "string" },
        ma_bien_the: { bsonType: "string" },
        ten_bien_the: { bsonType: "string" },
        link_anh_bien_the: { bsonType: ["string", "null"] },
        gia_ban: { bsonType: "double" },
        gia_niem_yet: { bsonType: ["double", "null"] },
        so_luong_ton_kho: { bsonType: "int" },
        da_ban: { bsonType: ["int", "null"] },
        thong_so_ky_thuat_rieng: { bsonType: "array" },
        trang_thai: { bsonType: "string" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.product_variants.createIndex({ "ma_bien_the": 1 }, { unique: true });
db.product_variants.createIndex({ "ma_san_pham": 1 });


// -------------------------------------------------------------
// 10. Collection: carts (Model: Cart)
// -------------------------------------------------------------
db.createCollection('carts', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_gio_hang", "ma_nguoi_dung", "trang_thai"],
      properties: {
        ma_gio_hang: { bsonType: "string" },
        ma_nguoi_dung: { bsonType: "string" },
        trang_thai: { bsonType: "string" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.carts.createIndex({ "ma_gio_hang": 1 }, { unique: true });
db.carts.createIndex({ "ma_nguoi_dung": 1 }, { unique: true });


// -------------------------------------------------------------
// 11. Collection: cart_items (Model: CartItem)
// -------------------------------------------------------------
db.createCollection('cart_items', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_gio_hang", "ma_bien_the", "so_luong"],
      properties: {
        ma_gio_hang: { bsonType: "string" },
        ma_bien_the: { bsonType: "string" },
        so_luong: { bsonType: "int" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.cart_items.createIndex({ "ma_gio_hang": 1, "ma_bien_the": 1 }, { unique: true });
db.cart_items.createIndex({ "ma_gio_hang": 1 });
db.cart_items.createIndex({ "ma_bien_the": 1 });


// -------------------------------------------------------------
// 12. Collection: vouchers (Model: Voucher)
// -------------------------------------------------------------
db.createCollection('vouchers', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_voucher", "ten_voucher", "mo_ta", "loai_voucher", "hinh_thuc_giam", "gia_tri_giam", "tong_luot_dung", "da_dung", "bat_dau", "ket_thuc", "trang_thai"],
      properties: {
        ma_voucher: { bsonType: "string" },
        ten_voucher: { bsonType: "string" },
        mo_ta: { bsonType: "string" },
        loai_voucher: { bsonType: "string" },
        hinh_thuc_giam: { bsonType: "string" },
        gia_tri_giam: { bsonType: "double" },
        muc_giam_toi_da: { bsonType: ["double", "null"] },
        don_hang_toi_thieu: { bsonType: "double" },
        tong_luot_dung: { bsonType: "int" },
        da_dung: { bsonType: "int" },
        bat_dau: { bsonType: "date" },
        ket_thuc: { bsonType: "date" },
        trang_thai: { bsonType: "string" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.vouchers.createIndex({ "ma_voucher": 1 }, { unique: true });


// -------------------------------------------------------------
// 13. Collection: orders (Model: Order)
// -------------------------------------------------------------
db.createCollection('orders', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_don_hang", "ma_nguoi_dung", "ho_ten_nguoi_nhan", "so_dien_thoai_nhan", "dia_chi_giao_hang", "tong_tien_hang", "phi_van_chuyen", "gia_tri_giam_voucher", "tong_thanh_toan", "phuong_thuc_thanh_toan", "trang_thai"],
      properties: {
        ma_don_hang: { bsonType: "string" },
        ma_nguoi_dung: { bsonType: "string" },
        ho_ten_nguoi_nhan: { bsonType: "string" },
        so_dien_thoai_nhan: { bsonType: "string" },
        dia_chi_giao_hang: { bsonType: "string" },
        ghi_chu: { bsonType: ["string", "null"] },
        ma_voucher: { bsonType: ["string", "null"] },
        tong_tien_hang: { bsonType: "double" },
        phi_van_chuyen: { bsonType: "double" },
        gia_tri_giam_voucher: { bsonType: "double" },
        tong_thanh_toan: { bsonType: "double" },
        phuong_thuc_thanh_toan: { bsonType: "string" },
        trang_thai: { bsonType: "string" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.orders.createIndex({ "ma_don_hang": 1 }, { unique: true });
db.orders.createIndex({ "ma_nguoi_dung": 1 });


// -------------------------------------------------------------
// 14. Collection: order_items (Model: OrderItem)
// -------------------------------------------------------------
db.createCollection('order_items', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_don_hang", "ma_bien_the", "ten_bien_the", "link_anh_dai_dien", "so_luong", "gia_ban", "thanh_tien"],
      properties: {
        ma_chi_tiet_don_hang: { bsonType: ["string", "null"] },
        ma_flash_sales: { bsonType: ["string", "null"] },
        ma_don_hang: { bsonType: "string" },
        ma_bien_the: { bsonType: "string" },
        ten_bien_the: { bsonType: "string" },
        link_anh_dai_dien: { bsonType: "string" },
        so_luong: { bsonType: "int" },
        gia_ban: { bsonType: "double" },
        thanh_tien: { bsonType: "double" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.order_items.createIndex({ "ma_don_hang": 1, "ma_bien_the": 1 }, { unique: true });
db.order_items.createIndex({ "ma_don_hang": 1 });
db.order_items.createIndex({ "ma_bien_the": 1 });


// -------------------------------------------------------------
// 15. Collection: flash_sales (Model: FlashSales)
// -------------------------------------------------------------
db.createCollection('flash_sales', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_flash_sales", "trang_thai"],
      properties: {
        ma_flash_sales: { bsonType: "string" },
        ten_flash_sales: { bsonType: ["string", "null"] },
        mo_ta: { bsonType: ["string", "null"] },
        bat_dau: { bsonType: ["date", "null"] },
        ket_thuc: { bsonType: ["date", "null"] },
        trang_thai: { bsonType: "string" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.flash_sales.createIndex({ "ma_flash_sales": 1 }, { unique: true });


// -------------------------------------------------------------
// 16. Collection: flash_sale_items (Model: FlashSaleItem)
// -------------------------------------------------------------
db.createCollection('flash_sale_items', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_chi_tiet_flash_sales", "ma_flash_sales", "ma_bien_the", "gia_flash_sale", "so_luong_gioi_han", "trang_thai"],
      properties: {
        ma_chi_tiet_flash_sales: { bsonType: "string" },
        ma_flash_sales: { bsonType: "string" },
        ma_bien_the: { bsonType: "string" },
        gia_flash_sale: { bsonType: "double" },
        so_luong_gioi_han: { bsonType: "int" },
        so_luong_da_ban: { bsonType: "int" },
        gioi_han_moi_nguoi: { bsonType: "int" },
        trang_thai: { bsonType: "string" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.flash_sale_items.createIndex({ "ma_chi_tiet_flash_sales": 1 }, { unique: true });
db.flash_sale_items.createIndex({ "ma_flash_sales": 1 });
db.flash_sale_items.createIndex({ "ma_bien_the": 1 });


// -------------------------------------------------------------
// 17. Collection: reviews (Model: Review)
// -------------------------------------------------------------
db.createCollection('reviews', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_san_pham", "ma_bien_the", "ma_nguoi_dung", "ma_don_hang", "ma_chi_tiet_don_hang", "noi_dung", "trang_thai"],
      properties: {
        ma_danh_gia: { bsonType: ["string", "null"] },
        ma_san_pham: { bsonType: "string" },
        ma_bien_the: { bsonType: "string" },
        ma_nguoi_dung: { bsonType: "string" },
        ma_don_hang: { bsonType: "string" },
        ma_chi_tiet_don_hang: { bsonType: "string" },
        ten_bien_the: { bsonType: ["string", "null"] },
        so_sao: { bsonType: "int" },
        noi_dung: { bsonType: "string" },
        danh_sach_anh: { bsonType: ["array", "null"], items: { bsonType: "string" } },
        video: { bsonType: ["array", "null"], items: { bsonType: "string" } },
        is_anonymous: { bsonType: "bool" },
        trang_thai: { bsonType: "string" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.reviews.createIndex({ "ma_danh_gia": 1 }, { unique: true });
db.reviews.createIndex({ "ma_san_pham": 1 });
db.reviews.createIndex({ "ma_bien_the": 1 });
db.reviews.createIndex({ "ma_nguoi_dung": 1 });
db.reviews.createIndex({ "ma_don_hang": 1 });
db.reviews.createIndex({ "ma_chi_tiet_don_hang": 1 });


// -------------------------------------------------------------
// 18. Collection: review_replies (Model: ReviewReply)
// -------------------------------------------------------------
db.createCollection('review_replies', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_danh_gia", "ma_admin", "noi_dung", "is_updated", "trang_thai"],
      properties: {
        ma_phan_hoi: { bsonType: ["string", "null"] },
        ma_danh_gia: { bsonType: "string" },
        ma_admin: { bsonType: "string" },
        noi_dung: { bsonType: "string" },
        lich_su_phan_hoi: { bsonType: ["array", "null"] },
        is_updated: { bsonType: "bool" },
        trang_thai: { bsonType: "string" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.review_replies.createIndex({ "ma_phan_hoi": 1 }, { unique: true, sparse: true });
db.review_replies.createIndex({ "ma_danh_gia": 1 });
db.review_replies.createIndex({ "ma_admin": 1 });


// -------------------------------------------------------------
// 19. Collection: user_address (Model: UserAddress)
// -------------------------------------------------------------
db.createCollection('user_address', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_dia_chi", "ma_nguoi_dung", "ho_ten", "so_dien_thoai", "dia_chi_chi_tiet", "tinh_thanh", "quan_huyen", "phuong_xa"],
      properties: {
        ma_dia_chi: { bsonType: "string" },
        ma_nguoi_dung: { bsonType: "string" },
        ho_ten: { bsonType: "string" },
        so_dien_thoai: { bsonType: "string" },
        dia_chi_chi_tiet: { bsonType: "string" },
        tinh_thanh: { bsonType: "string" },
        quan_huyen: { bsonType: "string" },
        phuong_xa: { bsonType: "string" },
        is_default: { bsonType: "bool" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.user_address.createIndex({ "ma_dia_chi": 1 }, { unique: true });
db.user_address.createIndex({ "ma_nguoi_dung": 1 });


// -------------------------------------------------------------
// 20. Collection: agent_conversations (Model: AgentConversation)
// -------------------------------------------------------------
db.createCollection('agent_conversations', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["title", "is_deleted"],
      properties: {
        user_id: { bsonType: ["string", "null"] },
        title: { bsonType: "string" },
        is_deleted: { bsonType: "bool" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.agent_conversations.createIndex({ "user_id": 1, "updated_at": -1 });


// -------------------------------------------------------------
// 21. Collection: agent_conversation_messages (Model: AgentConversationMessage)
// -------------------------------------------------------------
db.createCollection('agent_conversation_messages', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["conversation_id", "agent", "role", "content"],
      properties: {
        conversation_id: { bsonType: "string" },
        user_id: { bsonType: ["string", "null"] },
        agent: { bsonType: "string" },
        role: { bsonType: "string" },
        content: { bsonType: "string" },
        attachments: { bsonType: ["array", "null"] },
        tool_calls: { bsonType: ["array", "null"] },
        tool_results: { bsonType: ["array", "null"] },
        usage: { bsonType: ["array", "null"] },
        meta: { bsonType: ["array", "null"] },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.agent_conversation_messages.createIndex({ "conversation_id": 1, "user_id": 1, "updated_at": 1 });
db.agent_conversation_messages.createIndex({ "user_id": 1 });


// -------------------------------------------------------------
// 22. Collection: notifications (Model: Notification)
// -------------------------------------------------------------
db.createCollection('notifications', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_thong_bao", "ma_nguoi_dung", "tieu_de", "noi_dung", "loai", "duong_dan", "da_doc"],
      properties: {
        ma_thong_bao: { bsonType: "string" },
        ma_nguoi_dung: { bsonType: "string" },
        tieu_de: { bsonType: "string" },
        noi_dung: { bsonType: "string" },
        loai: { bsonType: "string" },
        duong_dan: { bsonType: "string" },
        da_doc: { bsonType: "bool" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.notifications.createIndex({ "ma_thong_bao": 1 }, { unique: true });
db.notifications.createIndex({ "ma_nguoi_dung": 1 });


// -------------------------------------------------------------
// 23. Collection: banner_images (Model: BannerImage)
// -------------------------------------------------------------
db.createCollection('banner_images', {
  validator: {
    $jsonSchema: {
      bsonType: "object",
      required: ["ma_banner", "image_url", "thu_tu_hien_thi", "trang_thai"],
      properties: {
        ma_banner: { bsonType: "string" },
        image_url: { bsonType: "string" },
        tieu_de: { bsonType: ["string", "null"] },
        mo_ta: { bsonType: ["string", "null"] },
        lien_ket: { bsonType: ["string", "null"] },
        thu_tu_hien_thi: { bsonType: "int" },
        trang_thai: { bsonType: "bool" },
        created_at: { bsonType: ["date", "null"] },
        updated_at: { bsonType: ["date", "null"] }
      }
    }
  }
});
db.banner_images.createIndex({ "ma_banner": 1 }, { unique: true });
