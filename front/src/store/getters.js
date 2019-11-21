const getters = {
    sidebar: (state) => {return state.navigation.sidebar},
    title: (state) => {return state.navigation.title},
    header: (state) => {return state.navigation.header},
    user: (state) => {return state.user.user},
    location: (state) => {return state.user.location},
    mobile: (state) => {return  state.navigation.mobile},
    verified: (state) => {return state.user.user.email_verified_at},
    app: (state) => {return state.app.app},
    search: (state) => {return state.search.search},
    places: (state) => {return state.places.places},
    products: (state) => {return state.products},
    sellouts: (state) => {return state.sellout.sellouts},
    activeSellout: (state) => {return state.sellout.active},
    cart: (state) => {return state.cart.cart},
    cart_visible: (state) => {return state.cart.visible},
    userOrders: (state) => {return state.order.userOrders},
    orders_sync: (state) => {return state.order.order_sync},
    activeOrder: (state) => {return state.order.activeOrder}
};
export default getters;