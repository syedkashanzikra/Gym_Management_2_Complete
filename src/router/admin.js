export default [
  {
    path: "/",
    redirect: "/dashboard",
    component: () => import("layouts/AdminLayout.vue"),
    children: [
      {
        path: "dashboard",
        name: "Dashboard",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
        },
        component: () => import("pages/admins/IndexPage.vue"),
      },
      {
        path: "scanner",
        name: "Scanner",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
        },
        component: () => import("pages/admins/QrCodeScannerPage.vue"),
      },
      {
        path: "my-account",
        name: "My Account",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          title: "My Account",
        },
        component: () => import("pages/admins/MyAccountPage.vue"),
      },
    ],
  },
  {
    path: "/auth",
    component: () => import("layouts/AuthLayout.vue"),
    children: [
      {
        path: "login",
        meta: {
          auth: false,
          admin: true,
          guard: "admins",
          title: "Admin Login",
        },
        name: "Login",
        component: () => import("pages/core/auth/LoginPage.vue"),
      },
      {
        path: "forgot-password",
        meta: {
          auth: false,
          admin: true,
          guard: "admins",
        },
        name: "Forget Password",
        component: () => import("pages/core/auth/ForgotPasswordPage.vue"),
      },
      {
        path: "reset-password",
        meta: {
          auth: false,
          admin: true,
          guard: "admins",
        },
        name: "Reset Password",
        component: () => import("pages/core/auth/ResetPasswordPage.vue"),
      },
    ],
  },
  {
    path: "/staff",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Staff",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          base: "Staff",
          module: "Staff",
        },
        component: () => import("pages/core/staffs/StaffsPage.vue"),
      },
      {
        path: ":id",
        name: "Single Staff",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          base: "Staff",
          module: "Staff",
        },
        component: () => import("pages/core/staffs/StaffPage.vue"),
      },
    ],
  },
  {
    path: "/groups",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Groups",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          base: "Staff",
          module: "Groups",
        },
        component: () => import("pages/core/groups/GroupsPage.vue"),
      },
      {
        path: ":id",
        name: "Single Group",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          base: "Staff",
          module: "Groups",
        },
        component: () => import("pages/core/groups/GroupPage.vue"),
      },
    ],
  },
  {
    path: "/members",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Members",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Members",
          base: "Enquiry",
        },
        component: () => import("pages/admins/members/MembersPage.vue"),
      },
      {
        path: ":id",
        name: "Single Member",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Members",
          base: "Enquiry",
        },
        component: () => import("pages/admins/members/MemberPage.vue"),
      },
    ],
  },
  {
    path: "/reports",
    redirect: "/reports/daily",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "daily",
        name: "Daily Reports",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Reports",
          base: "Daily Reports",
          permission: "Daily Reports",
        },
        component: () => import("pages/admins/members/ReportsDailyPage.vue"),
      },
      {
        path: "monthly",
        name: "Monthly Reports",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Reports",
          base: "Daily Reports",
          permission: "Monthly Reports",
        },
        component: () =>
          import("src/pages/admins/members/ReportsMonthPage.vue"),
      },
      {
        path: "yearly",
        name: "Yearly Reports",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Reports",
          base: "Daily Reports",
          permission: "Yearly Reports",
        },
        component: () => import("pages/admins/members/ReportsYearPage.vue"),
      },
    ],
  },
  {
    path: "/enquiry",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Enquiry",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Members",
          base: "Enquiry",
        },
        component: () => import("pages/admins/members/EnquiryPage.vue"),
      },
    ],
  },
  {
    path: "/instructors",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Instructors",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Instructors",
        },
        component: () => import("pages/admins/instructors/InstructorsPage.vue"),
      },
      {
        path: ":id",
        name: "Single Instructor",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Instructors",
        },
        component: () => import("pages/admins/instructors/InstructorPage.vue"),
      },
    ],
  },
  {
    path: "/products",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Products",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Products",
        },
        component: () => import("pages/admins/products/ProductsPage.vue"),
      },
      {
        path: ":id",
        name: "Single Product",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Products",
          base: "Products",
        },
        component: () => import("pages/admins/products/ProductPage.vue"),
      },
      {
        path: ":product_id/variants/:variant_id",
        name: "Products Variant",
        meta: {
          auth: true,
          module: "Products",
          base: "Products",
        },
        component: () => import("pages/admins/products/VariantPage.vue"),
      },
      {
        path: "categories",
        name: "Categories",
        meta: {
          auth: true,
          module: "Products",
          base: "Products",
          permission: "Categories",
        },
        component: () =>
          import("pages/admins/products/categories/CategoriesPage.vue"),
      },
      {
        path: "categories/:id",
        name: "Single Category",
        meta: {
          auth: true,
          module: "Products",
          base: "Products",
          permission: "Categories",
        },
        component: () =>
          import("pages/admins/products/categories/CategoryPage.vue"),
      },
      {
        path: "collections",
        name: "Collections",
        meta: {
          auth: true,
          module: "Products",
          base: "Products",
          permission: "Collections",
        },
        component: () =>
          import("pages/admins/products/collections/CollectionsPage.vue"),
      },
      {
        path: "collections/:id",
        name: "Single Collection",
        meta: {
          auth: true,
          module: "Products",
          base: "Products",
          permission: "Collections",
        },
        component: () =>
          import("pages/admins/products/collections/CollectionPage.vue"),
      },
      {
        path: "attributes",
        name: "Attributes",
        meta: {
          auth: true,
          module: "Products",
          base: "Products",
          permission: "Attributes",
        },
        component: () =>
          import("pages/admins/products/attributes/AttributesPage.vue"),
      },
      {
        path: "attributes/:id",
        name: "Single Attribute",
        meta: {
          auth: true,
          module: "Products",
          base: "Products",
          permission: "Attributes",
        },
        component: () =>
          import("pages/admins/products/attributes/AttributePage.vue"),
      },
      {
        path: "inventory",
        name: "Inventory",
        meta: {
          auth: true,
          module: "Products",
          permission: "Inventory",
          base: "Products",
        },
        component: () => import("pages/admins/products/InventoryPage.vue"),
      },
    ],
  },
  {
    path: "/orders",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Orders",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Orders",
          base: "Orders",
        },
        component: () => import("pages/admins/orders/OrdersPage.vue"),
      },
      {
        path: "pos",
        name: "Pos",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Orders",
          base: "Orders",
        },
        component: () => import("pages/admins/orders/PosPage.vue"),
      },
      {
        path: ":id",
        name: "Single Order",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Orders",
          base: "Orders",
        },
        component: () => import("pages/admins/orders/OrderPage.vue"),
      },
      {
        path: "customers/:id",
        name: "Customer",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Orders",
          base: "Orders",
        },
        component: () => import("pages/admins/orders/CustomerPage.vue"),
      },
    ],
  },
  {
    path: "/pos",
    component: () => import("src/layouts/PosLayout.vue"),
    children: [
      {
        path: "",
        name: "Pos",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Orders",
          base: "Orders",
        },
        component: () => import("pages/admins/orders/PosPage.vue"),
      },
    ],
  },
  {
    path: "/classes",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Classes",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Classes",
          base: "Classes",
        },
        component: () => import("pages/admins/class-lists/ClassesPage.vue"),
      },
      {
        path: ":id",
        name: "Single Class",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Classes",
          base: "Classes",
        },
        component: () => import("pages/admins/class-lists/ClassPage.vue"),
      },
    ],
  },
  {
    path: "/enquiries",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Enquiries",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Enquiries",
          title: "Enquiries",
          base: "Enquiries",
          status: "Live",
        },
        component: () => import("pages/core/enquiries/EnquiriesPage.vue"),
      },
      {
        path: ":id",
        name: "Single Enquiry",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Enquiries",
          base: "Enquiries",
        },
        component: () => import("pages/core/enquiries/EnquiryPage.vue"),
      },
    ],
  },
  {
    path: "/tasks",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Tasks",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Tasks",
          status: "Live",
        },
        component: () => import("pages/core/tasks/TasksPage.vue"),
      },
      {
        path: ":id",
        name: "Single Task",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Tasks",
          base: "Tasks",
        },
        component: () => import("pages/core/tasks/TaskPage.vue"),
      },
    ],
  },
  {
    path: "/class-schedules",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Class Schedules",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Week Schedules",
          title: "Week Schedules",
          base: "Classes",
        },
        component: () => import("pages/admins/ClassSchedulesPage.vue"),
      },
    ],
  },
  {
    path: "/announcements",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Announcements",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Announcements",
        },
        component: () =>
          import("pages/admins/announcements/AnnouncementsPage.vue"),
      },
      {
        path: ":id",
        name: "Single Announcement",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Announcements",
        },
        component: () =>
          import("pages/admins/announcements/AnnouncementPage.vue"),
      },
    ],
  },
  {
    path: "/locations",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Locations",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Locations",
          base: "Classes",
        },
        component: () => import("pages/admins/locations/LocationsPage.vue"),
      },
      {
        path: ":id",
        name: "Single Location",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Locations",
          base: "Classes",
        },
        component: () => import("pages/admins/locations/LocationPage.vue"),
      },
    ],
  },
  {
    path: "/templates",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Templates",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Templates",
          base: "Classes",
        },
        component: () => import("pages/admins/templates/TemplatesPage.vue"),
      },
      {
        path: ":id",
        name: "Single Template",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Templates",
          base: "Classes",
        },
        component: () => import("pages/admins/templates/TemplatePage.vue"),
      },
    ],
  },
  {
    path: "/finance",
    redirect: "/finance/membership",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "membership",
        name: "Finance Membership",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Finance",
          base: "Finance Membership",
        },
        component: () => import("pages/admins/finance/MembershipPage.vue"),
      },
    ],
  },
  {
    path: "/plans",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Plans",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Finance",
          permission: "Plans",
          base: "Finance Membership",
        },
        component: () => import("pages/admins/plans/PlansPage.vue"),
      },
      {
        path: ":id",
        name: "Single Plan",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Finance",
          permission: "Plans",
          base: "Finance Membership",
        },
        component: () => import("pages/admins/plans/PlanPage.vue"),
      },
    ],
  },
  {
    path: "/week-schedules",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Week Schedules",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Week Schedules",
        },
        component: () =>
          import("pages/admins/week-schedules/WeekSchedulesPage.vue"),
      },
      {
        path: ":id",
        name: "Single Week Schedule",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          module: "Week Schedules",
        },
        component: () =>
          import("pages/admins/week-schedules/WeekSchedulePage.vue"),
      },
    ],
  },
  {
    path: "/settings",
    component: () => import("src/layouts/AdminLayout.vue"),
    children: [
      {
        path: "",
        name: "Settings",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          title: "Settings",
          module: "Settings",
        },
        component: () => import("pages/admins/SettingsPage.vue"),
      },
      {
        path: "locations",
        name: "Shop Locations",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          title: "Shop Locations",
          module: "Settings",
        },
        component: () => import("pages/admins/settings/ShopLocationsPage.vue"),
      },
      {
        path: "taxes",
        name: "Taxes",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          title: "Taxes and duties",
          module: "Settings",
        },
        component: () => import("pages/admins/settings/TaxesPage.vue"),
      },
      {
        path: "payments",
        name: "Payment Methods",
        meta: {
          auth: true,
          admin: true,
          guard: "admins",
          title: "Payment methods",
          module: "Settings",
        },
        component: () => import("pages/admins/settings/PaymentMethodsPage.vue"),
      },
    ],
  },
];
