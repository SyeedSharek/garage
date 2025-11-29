import { ref } from 'vue';

const DB_NAME = 'GarageOrderDB';
const DB_VERSION = 2; // Incremented to add services store
const CART_STORE = 'cart';
const SERVICES_STORE = 'services';

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
      const oldVersion = event.oldVersion;

      // Create cart store (if upgrading from version 0 or 1)
      if (oldVersion < 1 || !database.objectStoreNames.contains(CART_STORE)) {
        if (!database.objectStoreNames.contains(CART_STORE)) {
          database.createObjectStore(CART_STORE, { keyPath: 'id' });
        }
      }

      // Create services store (if upgrading from version 1 or earlier)
      if (oldVersion < 2 || !database.objectStoreNames.contains(SERVICES_STORE)) {
        if (!database.objectStoreNames.contains(SERVICES_STORE)) {
          const servicesStore = database.createObjectStore(SERVICES_STORE, { keyPath: 'id' });
          servicesStore.createIndex('is_active', 'is_active', { unique: false });
        }
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
      const transaction = database.transaction([CART_STORE], 'readonly');
      const store = transaction.objectStore(CART_STORE);
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
      const transaction = database.transaction([CART_STORE], 'readwrite');
      const store = transaction.objectStore(CART_STORE);
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
      const transaction = database.transaction([CART_STORE], 'readwrite');
      const store = transaction.objectStore(CART_STORE);
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
      const transaction = database.transaction([CART_STORE], 'readwrite');
      const store = transaction.objectStore(CART_STORE);
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
 * Save services to IndexedDB
 */
export const saveServices = async (services) => {
  try {
    const database = await initDB();
    return new Promise((resolve, reject) => {
      const transaction = database.transaction([SERVICES_STORE], 'readwrite');
      const store = transaction.objectStore(SERVICES_STORE);

      // Clear existing services first
      store.clear();

      // Add all services
      const requests = services.map(service => store.put({
        id: service.id,
        code: service.code,
        name: service.name,
        unit_price: service.unit_price,
        unit: service.unit,
        formatted_price: service.formatted_price,
        is_active: service.is_active,
        sort_order: service.sort_order,
        cached_at: Date.now(), // Track when data was cached
      }));

      Promise.all(requests.map(req => new Promise((res, rej) => {
        req.onsuccess = () => res();
        req.onerror = () => rej(req.error);
      })))
        .then(() => resolve())
        .catch(reject);
    });
  } catch (error) {
    console.error('Error saving services:', error);
    throw error;
  }
};

/**
 * Get all services from IndexedDB
 */
export const getServices = async () => {
  try {
    const database = await initDB();
    return new Promise((resolve, reject) => {
      const transaction = database.transaction([SERVICES_STORE], 'readonly');
      const store = transaction.objectStore(SERVICES_STORE);
      const request = store.getAll();

      request.onsuccess = () => {
        resolve(request.result || []);
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  } catch (error) {
    console.error('Error getting services:', error);
    return [];
  }
};

/**
 * Composable for managing cart with IndexedDB
 */
export const useCartDB = () => {
  const cartItems = ref([]);
  const isLoading = ref(false);
  const services = ref([]);
  const servicesLoading = ref(false);

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
   * This function is connected to IndexedDB and persists all changes
   */
  const addToCart = async (service) => {
    try {
      // Ensure IndexedDB is initialized
      await initDB();

      // Check if item already exists by service_id
      const existingIndex = cartItems.value.findIndex(item => item.service_id === service.id);

      if (existingIndex !== -1) {
        // Update existing item - increment quantity
        const existingItem = cartItems.value[existingIndex];
        existingItem.quantity += 1;
        // Save to IndexedDB
        await saveCartItem(existingItem);
      } else {
        // Add new item to cart
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
        // Add to reactive array
        cartItems.value.push(newItem);
        // Save to IndexedDB
        await saveCartItem(newItem);
      }
    } catch (error) {
      console.error('Error adding to cart (IndexedDB):', error);
      throw error;
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

  /**
   * Load services from IndexedDB
   */
  const loadServices = async () => {
    servicesLoading.value = true;
    try {
      const items = await getServices();
      services.value = items;
      return items;
    } catch (error) {
      console.error('Error loading services:', error);
      return [];
    } finally {
      servicesLoading.value = false;
    }
  };

  /**
   * Save services to IndexedDB
   */
  const saveServicesToDB = async (servicesData) => {
    try {
      await saveServices(servicesData);
      services.value = servicesData;
    } catch (error) {
      console.error('Error saving services:', error);
      throw error;
    }
  };

  return {
    cartItems,
    isLoading,
    services,
    servicesLoading,
    loadCart,
    loadServices,
    saveServicesToDB,
    addToCart,
    updateQuantity,
    increaseQuantity,
    decreaseQuantity,
    removeFromCart,
    clearAll,
  };
};

