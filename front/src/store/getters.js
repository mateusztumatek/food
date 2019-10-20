const getters = {
    sidebar: (state) => {return state.navigation.sidebar},
    title: (state) => {return state.navigation.title},
    user: (state) => {return state.user.user},
    mobile: (state) => {return  state.navigation.mobile},
    verified: (state) => {return state.user.user.email_verified_at},
    app: (state) => {return state.app.app},
    search: (state) => {return state.search.search}
};
export default getters;