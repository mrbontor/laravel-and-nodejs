const fs = require('fs')
const iniParser = require('./libs/iniParser')
const logging = require('./libs/logging')
const args = require('minimist')(process.argv.slice(2));
const bodyParser = require('body-parser')
const mongo = require('./libs/mongo')

const express = require('express')
const app = express()

process.env.TZ = 'Asia/Jakarta'
// default config if config file is not provided
let config = {
    log: {
        path: "var/log/",
        level: "debug"
    }
}

if (args.h || args.help) {
  // TODO: print USAGE
  console.log("Usage: node " + __filename + " --config");
  process.exit(-1);
}

// overwrite default config with config file
configFile = args.c || args.config || './configs/confServices.ini'
config = iniParser.init(config, configFile, args)
config.log.level = args.logLevel || config.log.level

const take_port = config.app.port;
const port = take_port || process.env.PORT;

// Initialize logging library
logging.init({
  path: config.log.path,
  level: config.log.level
})

// Initialize MongoDB database
mongo.init(config.mongodb)
mongo.ping( (err, res) => {
  if (err) return logging.error(err.stack)

  if ( ! res.ok)
    return logging.error(`[MONGO] CONNECTION NOT ESTABLISHED. Ping Command not returned OK`)

  logging.debug(`[MONGO] CONNECTION ESTABLISHED`)
})



logging.info(`[CONFIG] ${JSON.stringify(iniParser.get())}`)

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

const routes = require('./router/router');
routes(app);


app.listen(port);
logging.info('[app] API SERVICES STARTED on ' + port );
// process.setMaxListeners(0);
