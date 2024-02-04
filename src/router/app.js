export default [
  {
    path: "/",
    redirect: "/dashboard",
    component: () => import("layouts/AppLayout.vue"),
    children: [
      {
        path: "dashboard",
        name: "Dashboard",
        meta: {
          auth: true,
          guard: "users",
        },
        component: () => import("pages/IndexPage.vue"),
      },
    ],
  },
  {
    path: "/enquiries",
    component: () => import("src/layouts/AppLayout.vue"),
    children: [
      {
        path: "",
        name: "Enquiries",
        meta: {
          auth: true,
          guard: "users",
          title: "Messages",
          base: "Enquiries",
          status: "All",
        },
        component: () => import("pages/core/enquiries/EnquiriesPage.vue"),
      },
      {
        path: ":id",
        name: "Single Enquiry",
        meta: {
          auth: true,
          guard: "users",
          title: "Create Message",
          base: "Enquiries",
        },
        component: () => import("pages/core/enquiries/EnquiryPage.vue"),
      },
    ],
  },
  {
    path: "/guest-passes",
    component: () => import("src/layouts/AppLayout.vue"),
    children: [
      {
        path: "",
        name: "Guest Passes",
        meta: {
          auth: true,
          guard: "users",
          base: "Guest Passes",
        },
        component: () => import("pages/guest-passes/GuestPassesPage.vue"),
      },
      {
        path: ":id",
        name: "Single Guest Pass",
        meta: {
          auth: true,
          guard: "users",
          base: "Guest Passes",
        },
        component: () => import("pages/guest-passes/GuestPassPage.vue"),
      },
    ],
  },
  {
    path: "/shop",
    component: () => import("src/layouts/AppLayout.vue"),
    children: [
      {
        path: "",
        name: "Shop",
        meta: {
          auth: true,
          guard: "users",
          base: "Shop",
        },
        component: () => import("pages/users/ShopPage.vue"),
      },
      {
        path: "products",
        redirect: "/shop",
      },
      {
        path: "products/:slug",
        name: "Product",
        meta: {
          auth: true,
          admin: true,
          guard: "users",
          base: "Shop",
        },
        component: () => import("pages/users/shop/ProductPage.vue"),
      },
      {
        path: "cart",
        name: "Cart",
        meta: {
          auth: true,
          guard: "users",
          base: "Shop",
        },
        component: () => import("pages/users/shop/CartPage.vue"),
      },
    ],
  },
  {
    path: "/classes",
    redirect: "/classes/bookable",
    component: () => import("src/layouts/AppLayout.vue"),
    children: [
      {
        path: "bookable",
        name: "Bookable",
        meta: {
          auth: true,
          admin: true,
          guard: "users",
          title: "Bookable Classes",
          base: "Classes",
        },
        component: () => import("pages/users/BookablePage.vue"),
      },
      {
        path: "booked",
        name: "Classes",
        meta: {
          auth: true,
          admin: true,
          guard: "users",
          title: "Booked Classes",
          base: "Classes",
        },
        component: () => import("pages/users/ClassSchedulesPage.vue"),
      },
    ],
  },
  {
    path: "/documents",
    component: () => import("layouts/AppLayout.vue"),
    children: [
      {
        path: "",
        name: "Documents",
        meta: {
          auth: true,
        },
        component: () => import("pages/users/DocumentsPage.vue"),
      },
    ],
  },
  {
    path: "/",
    component: () => import("layouts/AppLayout.vue"),
    children: [
      {
        path: "my-account",
        name: "My Account",
        meta: {
          auth: true,
          title: "My Account",
          guard: "users",
        },
        component: () => import("pages/MyAccountPage.vue"),
      },
      {
        path: "billing",
        name: "Billing",
        meta: {
          auth: true,
          title: "Billing",
          guard: "users",
        },
        component: () => import("pages/BillingPage.vue"),
      },
      {
        path: "subscription",
        name: "Subscription",
        meta: {
          auth: true,
          title: "Subscription",
          guard: "users",
        },
        component: () => import("pages/SubscriptionPage.vue"),
      },
    ],
  },
  {
    path: "/sign-up",
    component: () => import("layouts/BlankLayout.vue"),
    children: [
      {
        path: "",
        name: "Sign up",
        meta: {
          auth: false,
          guard: "users",
        },
        component: () => import("pages/SignUpPage.vue"),
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
          guard: "users",
          title: "Members Login",
        },
        name: "Login",
        component: () => import("pages/core/auth/LoginPage.vue"),
      },
      {
        path: "forgot-password",
        meta: {
          auth: false,
          guard: "users",
        },
        name: "Forget Password",
        component: () => import("pages/core/auth/ForgotPasswordPage.vue"),
      },
      {
        path: "reset-password",
        meta: {
          auth: false,
          guard: "users",
        },
        name: "Reset Password",
        component: () => import("pages/core/auth/ResetPasswordPage.vue"),
      },
    ],
  },
];
