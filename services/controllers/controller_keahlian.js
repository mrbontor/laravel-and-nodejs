const fs = require('fs')
const Ajv = require('ajv');
const iniParser = require('../libs/iniParser')
const logging = require('../libs/logging')
const util = require('../libs/utils')
const dbcon = require('./dbcontroller')

const createTempalte = fs.readFileSync('./data/create_keahlian.json', 'utf-8')
const updateTemplate = fs.readFileSync('./data/update_keahlian.json', 'utf-8')

//show All error if data not valid
const ajv = new Ajv({
    allErrors: true,
    loopRequired: Infinity
});
// options can be passed, e.g. {allErrors: true}
const validateCreate = ajv.compile(JSON.parse(createTempalte))
const validateUpdate = ajv.compile(JSON.parse(updateTemplate))

let config = iniParser.get()

// list keahlian
async function findDataKeahlian(req, res) {
    try {
        let response = {status: false}

        let list = await dbcon.findData(config.mongodb.coll_keahlian)
        logging.debug(`[findDataKeahlian] >>>> ${JSON.stringify(list)}`)

        if (list.length < 0) {
            response.message = `No data found`
            response.data = []
            res.status(400).send(response)
            return ;
        }

        response = {
            status: true,
            message: 'Successfully load data',
            data: list
        }
        res.status(200).send(response)
    } catch (e) {
        logging.error(`[findDataKeahlian] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

//get keahlian by id
async function findByIdKeahlian(req, res) {
    try {
        let response = {status: false}

        let result = await dbcon.findDataById(config.mongodb.coll_keahlian, {_id: require('mongodb').ObjectId(req.params.id)})
        logging.debug(`[findByIdKeahlian] >>>> ${JSON.stringify(result)}`)

        if (result) {
            response = {
                status: true,
                message: 'Successfully load data',
                data: result
            }
            res.status(200).send(response)
        } else {
            response = {
                message: 'Failed to load data',
                data: []
            }
            res.status(400).send(response)
        }
    } catch (e) {
        logging.error(`[findByIdKeahlian] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

// create data keahlian
function createDataKeahlian(req, res) {
    let data = {
        nama: req.body.nama,
        keterangan: req.body.keterangan,
        createAt: util.dateConverter(new Date()),
        updateAt: util.dateConverter(new Date())
    }
    dataValidate(data, 'create')
    .then(async function() {
        let response = {status: false}

        let getKeahlian = await dbcon.findDataById(config.mongodb.coll_keahlian, {nama: req.body.nama})
        logging.debug(`[isExist] >>>> ${JSON.stringify(getKeahlian !== null)}`)
        if (getKeahlian !== null) {
            response.message= `This ${req.body.nama} already used`
            response.data= []

            res.status(400).send(response)
            return ;
        }

        let result = await dbcon.saveData(config.mongodb.coll_keahlian, data)
        logging.debug(`[createDataKeahlian] >>>> ${JSON.stringify(result)}`)
        if (typeof result._id === 'undefined') {
            response.message = `Failed insert data`
            response.data = []
            res.status(400).send(response)
            return ;
        }

        response = {
            status: true,
            message: 'Successfully insert data',
            data: data
        }
        res.status(200).send(response)
    })
    .catch(function(err) { //catch error form validator
        logging.error(`[createDataKeahlian] >>>> ${JSON.stringify(err.stack)}`)
        let response = util.handleErrorValidation(err)
        res.status(400).send(response)
    })
}

// update data keahlian
function updateDataKeahlian(req, res) {
    let data = {
        $set: {
            nama: req.body.nama,
            keterangan: req.body.keterangan,
            updateAt: util.dateConverter(new Date())
        }
    }
    let clause = {_id: require('mongodb').ObjectId(req.body.id)}
    dataValidate(data.$set, 'update')
    .then(async function() {
        let response = {status: false}

        let result = await dbcon.updateData(config.mongodb.coll_keahlian, clause, data)
        logging.debug(`[updateDataKeahlian] >>>> ${JSON.stringify(result)}`)
        if (result.result.n === 1) {
            response = {
                status: true,
                message: 'Successfully update data',
                data: result.result
            }
            res.status(200).send(response)
        } else {
            response = {
                message: 'Failed to update data',
                data: []
            }
            res.status(400).send(response)
        }
    })
    .catch(function(err) { //catch error form validator
        logging.error(`[updateDataKeahlian] >>>> ${JSON.stringify(err.stack)}`)
        let response = util.handleErrorValidation(err)
        res.status(400).send(response)
    })
}

// delete Data
async function deleteDataKeahlian(req, res) {
    try {
        let response = {status: false}
        let result = await dbcon.deleteData(config.mongodb.coll_keahlian, {_id: require('mongodb').ObjectId(req.params.id)})
        logging.debug(`[deleteData] >>>> ${JSON.stringify(result)}`)

        if (result.result.n === 1) {
            response = {
                status: true,
                message: 'Successfully delete data',
                data: result.result
            }
            res.status(200).send(response)
        } else {
            response = {
                message: 'Failed to delete data',
                data: []
            }
            res.status(400).send(response)
        }
    } catch (e) {
        logging.error(`[deleteData] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

async function searchKeahlianAutocomplete(req, res) {
    config = iniParser.get()
    try {
        let q = {
            $or: [
                {nama: { $regex: req.params.search, $options: "i" }},
                {alamat: { $regex: req.params.search, $options: "i" }}
            ]
        }
        let opt = {
            limit: 10,
            sort: {nama: 1} // or use like ==> [["nama",'1']]
        }
        let response = {status: false}

        let result = await dbcon.findData(config.mongodb.coll_keahlian, q, opt)
        logging.debug(`[searchKeahlianAutocomplete] >>>> ${JSON.stringify(result)}`)

        if (result.length > 0) {
            response = {
                status: true,
                message: 'Successfully load data',
                data: result
            }
            res.status(200).send(response)
        } else {
            response = {
                message: 'No data found',
                data: []
            }
            res.status(400).send(response)
        }
    } catch (e) {
        logging.error(`[searchKeahlianAutocomplete] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No fata found',
            data: []
        }
        res.status(400).send(response)
    }
}

//validate data post
function dataValidate(data, metode) {
    return new Promise((next, reject) => {
        switch (metode) {
            case 'create':
                validator = validateCreate(data)
                validate = validateCreate
                break;
            case 'update':
                validator = validateUpdate(data)
                validate = validateUpdate
                break;
        }

        if (!validator) {
            logging.error(JSON.stringify(validate.errors));
            reject(validate.errors)
        }
        next()
    })
}

module.exports = {
    findDataKeahlian,
    findByIdKeahlian,
    createDataKeahlian,
    updateDataKeahlian,
    deleteDataKeahlian,
    searchKeahlianAutocomplete
};
