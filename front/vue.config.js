module.exports = {
    publicPath: process.env.NODE_ENV === 'production'
        ? '/'
        : '/',
    pwa:{
        /*workboxPluginMode: 'InjectManifest',
        workboxOptions: {
            navigateFallback: '/index.html',
            runtimeCaching: [
                {
                    urlPattern: new RegExp('^http://192.168.1.4:9000'),
                    handler: 'networkFirst',
                    options:{
                        swSrc: 'dev/sw.js',
                        networkTimeoutSeconds: 20,
                        cacheName: 'api-cache',
                        cacheableResponse:{
                            statuses: [0, 200]
                        }
                    }
                }
            ]
        }*/
    },
    filenameHashing: false,
    chainWebpack: config => {
        config.module
            .rule('svg')
            .use('file-loader')
            .options({
                name: '[name].[ext]',
                outputPath: ''
            });
    }
}