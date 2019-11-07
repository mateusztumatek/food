const getters = {
    sidebar: (state) => {return state.navigation.sidebar},
    title: (state) => {return state.navigation.title},
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
};
export default getters;