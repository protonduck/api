if (process.env.section) {
    require(`${__dirname}/webpack.${process.env.section}.mix.js`);
}
