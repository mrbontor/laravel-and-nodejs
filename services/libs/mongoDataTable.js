const logging = require('./logging')
const iniParser = require('./iniParser');

const MongoClient = require('mongodb').MongoClient;
const MongoDataTable = require('mongo-datatable');

let config = iniParser.get()
function connectionUri() {
    let db = {
        host: config.mongodb.host,
        port: config.mongodb.port,
        dbname: config.mongodb.database,
        username: config.mongodb.user,
        password: config.mongodb.password,
        get connectionUri() {
            return 'mongodb://' + this.host + ':' + this.port + '/' + this.dbname;
        }
    }
    console.log(db);
    return db
}

function mongoDataTable(collection, options) {
    // let url = `mongodb://${config.mongodb.user}:${config.mongodb.password}@${config.mongodb.host}:${config.mongodb.port}/${config.mongodb.config.database}`
    MongoClient.connect(connectionUri(), function(err, client) {
        if (err) {
            console.error(err);
        }
        console.log(config);

        var dbname = config.mongodb.database;
        var db = client.db(dbname);

        console.log(db)
        new MongoDataTable(db).get(collection, options, function(err, result) {

            if (err) {
                console.error(err);
            }

            return result
        });
    });
}

module.exports = {mongoDataTable};
