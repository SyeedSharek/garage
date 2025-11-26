resources/js
│  ├─ assets/                  # Images, fonts, icons
│  ├─ components/              # Reusable UI components
│  │  ├─ layout/               # App layout components
│  │  │  ├─ AppHeader.vue
│  │  │  ├─ AppSidebar.vue
│  │  │  ├─ AppFooter.vue
│  │  │  └─ MainLayout.vue
│  │  ├─ ui/                   # Small reusable UI elements
│  │  │  ├─ Button.vue
│  │  │  ├─ Card.vue
│  │  │  ├─ Modal.vue
│  │  │  ├─ Table.vue
│  │  │  ├─ Dropdown.vue
│  │  │  └─ FormInput.vue
│  │  ├─ forms/                # Form-specific components
│  │  │  ├─ VehicleForm.vue
│  │  │  ├─ CustomerForm.vue
│  │  │  └─ ServiceForm.vue
│  │  ├─ tables/               # Table-specific components
│  │  │  ├─ VehicleTable.vue
│  │  │  ├─ CustomerTable.vue
│  │  │  └─ ServiceTable.vue
│  │  └─ notifications/        # Snackbar, Toast, Alerts
│  │     ├─ Toast.vue
│  │     └─ Snackbar.vue
│  ├─ layouts/                 # Pages layout wrappers
│  │  ├─ AuthLayout.vue
│  │  └─ DashboardLayout.vue
│  ├─ pages/                   # Inertia pages
│  │  ├─ Dashboard.vue
│  │  ├─ Vehicles/
│  │  │  ├─ VehicleList.vue
│  │  │  ├─ VehicleCreate.vue
│  │  │  └─ VehicleEdit.vue
│  │  ├─ Customers/
│  │  │  ├─ CustomerList.vue
│  │  │  ├─ CustomerCreate.vue
│  │  │  └─ CustomerEdit.vue
│  │  ├─ Services/
│  │  │  ├─ ServiceList.vue
│  │  │  ├─ ServiceCreate.vue
│  │  │  └─ ServiceEdit.vue
│  │  └─ Reports/
│  │     ├─ SalesReport.vue
│  │     └─ ServiceReport.vue
│  ├─ router/                  # Vue Router files
│  │  └─ index.js
│  ├─ store/                   # Pinia/Vuex store
│  │  ├─ index.js
│  │  ├─ vehicle.js
│  │  ├─ customer.js
│  │  └─ service.js
│  ├─ composables/             # Reusable logic (composition API)
│  │  ├─ useVehicle.js
│  │  ├─ useCustomer.js
│  │  └─ useService.js
│  └─ App.vue
│  └─ main.js
