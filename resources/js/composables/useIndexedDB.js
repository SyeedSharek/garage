import { ref } from 'vue';

const DB_NAME = 'GarageOrderDB';
const DB_VERSION = 1;
const STORE_NAME = 'cart';

let db = null;

/**
 * Initialize IndexedDB
 */
const initDB = () => {
  return new Promise((resolve, reject) => {
    if (db) {
      resolve(db);
      return;
    }

    const request = indexedDB.open(DB_NAME, DB_VERSION);

    request.onerror = () => {
      reject(request.error);
    };

    request.onsuccess = () => {
      db = request.result;
      resolve(db);
    };

    request.onupgradeneeded = (event) => {
      const database = event.target.result;
      if (!database.objectStoreNames.contains(STORE_NAME)) {
        database.createObjectStore(STORE_NAME, { keyPath: 'id' });
      }
    };
  });
};

/**
 * Get all cart items from IndexedDB
 */
export const getCartItems = async () => {
  try {
    const database = await initDB();
    return new Promise((resolve, reject) => {
      const transaction = database.transaction([STORE_NAME], 'readonly');
      const store = transaction.objectStore(STORE_NAME);
      const request = store.getAll();

      request.onsuccess = () => {
        resolve(request.result || []);
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  } catch (error) {
    console.error('Error getting cart items:', error);
    return [];
  }
};

/**
 * Save cart item to IndexedDB
 */
export const saveCartItem = async (item) => {
  try {
    const database = await initDB();
    return new Promise((resolve, reject) => {
      const transaction = database.transaction([STORE_NAME], 'readwrite');
      const store = transaction.objectStore(STORE_NAME);
      const request = store.put(item);

      request.onsuccess = () => {
        resolve(request.result);
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  } catch (error) {
    console.error('Error saving cart item:', error);
    throw error;
  }
};

/**
 * Remove cart item from IndexedDB
 */
export const removeCartItem = async (id) => {
  try {
    const database = await initDB();
    return new Promise((resolve, reject) => {
      const transaction = database.transaction([STORE_NAME], 'readwrite');
      const store = transaction.objectStore(STORE_NAME);
      const request = store.delete(id);

      request.onsuccess = () => {
        resolve();
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  } catch (error) {
    console.error('Error removing cart item:', error);
    throw error;
  }
};

/**
 * Clear all cart items from IndexedDB
 */
export const clearCart = async () => {
  try {
    const database = await initDB();
    return new Promise((resolve, reject) => {
      const transaction = database.transaction([STORE_NAME], 'readwrite');
      const store = transaction.objectStore(STORE_NAME);
      const request = store.clear();

      request.onsuccess = () => {
        resolve();
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  } catch (error) {
    console.error('Error clearing cart:', error);
    throw error;
  }
};

/**
 * Composable for managing cart with IndexedDB
 */
export const useCartDB = () => {
  const cartItems = ref([]);
  const isLoading = ref(false);

  /**
   * Load cart items from IndexedDB
   */
  const loadCart = async () => {
    isLoading.value = true;
    try {
      const items = await getCartItems();
      cartItems.value = items;
    } catch (error) {
      console.error('Error loading cart:', error);
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Add or update item in cart
   */
  const addToCart = async (service) => {
    try {
      const existingItem = cartItems.value.find(item => item.service_id === service.id);

      if (existingItem) {
        existingItem.quantity += 1;
        await saveCartItem(existingItem);
      } else {
        const newItem = {
          id: `cart_${service.id}_${Date.now()}`,
          service_id: service.id,
          code: service.code,
          name: service.name,
          unit_price: service.unit_price,
          formatted_price: service.formatted_price,
          unit: service.unit,
          quantity: 1,
        };
        cartItems.value.push(newItem);
        await saveCartItem(newItem);
      }
    } catch (error) {
      console.error('Error adding to cart:', error);
    }
  };

  /**
   * Update item quantity
   */
  const updateQuantity = async (index, newQuantity) => {
    if (newQuantity < 1) return;

    try {
      const item = cartItems.value[index];
      if (item) {
        item.quantity = newQuantity;
        await saveCartItem(item);
      }
    } catch (error) {
      console.error('Error updating quantity:', error);
    }
  };

  /**
   * Increase quantity
   */
  const increaseQuantity = async (index) => {
    const item = cartItems.value[index];
    if (item) {
      await updateQuantity(index, item.quantity + 1);
    }
  };

  /**
   * Decrease quantity
   */
  const decreaseQuantity = async (index) => {
    const item = cartItems.value[index];
    if (item && item.quantity > 1) {
      await updateQuantity(index, item.quantity - 1);
    }
  };

  /**
   * Remove item from cart
   */
  const removeFromCart = async (index) => {
    try {
      const item = cartItems.value[index];
      if (item) {
        await removeCartItem(item.id);
        cartItems.value.splice(index, 1);
      }
    } catch (error) {
      console.error('Error removing from cart:', error);
    }
  };

  /**
   * Clear all cart items
   */
  const clearAll = async () => {
    try {
      await clearCart();
      cartItems.value = [];
    } catch (error) {
      console.error('Error clearing cart:', error);
    }
  };

  return {
    cartItems,
    isLoading,
    loadCart,
    addToCart,
    updateQuantity,
    increaseQuantity,
    decreaseQuantity,
    removeFromCart,
    clearAll,
  };
};

