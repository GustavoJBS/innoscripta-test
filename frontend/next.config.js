/** @type {import('next').NextConfig} */
const nextConfig = {
    webpack: (config, _) => ({
        ...config,
        watchOptions: {
          ...config.watchOptions,
          poll: 400,
          aggregateTimeout: 300,
        },
    }),
}

module.exports = nextConfig
