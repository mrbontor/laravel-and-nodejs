const fs = require('fs')
const Ajv = require('ajv');
const iniParser = require('../libs/iniParser')
const logging = require('../libs/logging')
const util = require('../libs/utils')
const dbcon = require('./dbcontroller')

const createTempalte = fs.readFileSync('./data/create_jadwal.json', 'utf-8')
const updateTemplate = fs.readFileSync('./data/update_jadwal.json', 'utf-8')

//show All error if data not valid
const ajv = new Ajv({
    allErrors: true,
    loopRequired: Infinity
});
// options can be passed, e.g. {allErrors: true}
const validateCreate = ajv.compile(JSON.parse(createTempalte))
const validateUpdate = ajv.compile(JSON.parse(updateTemplate))

let config = iniParser.get()

// list jadwal
async function findDataJadwal(req, res) {
    try {
        let response = {status: false}

        let list = await dbcon.findData(config.mongodb.coll_jadwal)
        logging.debug(`[findDataJadwal] >>>> ${JSON.stringify(list)}`)

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
        logging.error(`[findDataJadwal] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

async function findByIdJadwal(req, res) {
    try {
        let response = {status: false}

        let result = await dbcon.findDataById(config.mongodb.coll_jadwal, {_id: require('mongodb').ObjectId(req.params.id)})
        logging.debug(`[findByIdJadwal] >>>> ${JSON.stringify(result)}`)

        if (result) {
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
        logging.error(`[findByIdJadwal] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

//get jadwal by id dokter
async function findJadwalByIdDokter(req, res) {
    try {
        let response = {status: false}

        let result = await dbcon.findData(config.mongodb.coll_jadwal, {dokter_id: require('mongodb').ObjectId(req.params.id)})
        logging.debug(`[findJadwalByIdDokter] >>>> ${JSON.stringify(result)}`)

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
        logging.error(`[findJadwalByIdDokter] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

// create data jadwal
function createDataJadwal(req, res) {
    let data = {
        hari: req.body.hari,
        buka_jam: req.body.buka_jam,
        tutup_jam: req.body.tutup_jam,
        dokter_id:  require('mongodb').ObjectId(req.body.dokter_id),
        createAt: util.dateConverter(new Date()),
        updateAt: util.dateConverter(new Date())
    }
    dataValidate(data, 'create')
    .then(async function() {
        let response = {status: false}
        let check = {
            dokter_id: require('mongodb').ObjectId(req.body.dokter_id),
            buka_jam: req.body.buka_jam,
            tutup_jam: req.body.tutup_jam,
            hari: req.body.hari
        }
        let isExist = await dbcon.checkData(config.mongodb.coll_jadwal, check)
        logging.debug(`[isExist] >>>> ${JSON.stringify(isExist)}`)
        if (isExist.length > 0) {
            response.message= `Data already saved, try another one`
            response.data= []

            res.status(400).send(response)
            return ;
        }

        let result = await dbcon.saveData(config.mongodb.coll_jadwal, data)
        logging.debug(`[createDataJadwal] >>>> ${JSON.stringify(result)}`)
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
        logging.error(`[createDataJadwal] >>>> ${JSON.stringify(err.stack)}`)
        let response = util.handleErrorValidation(err)
        res.status(400).send(response)
    })
}

// update data jadwal
function updateDataJadwal(req, res) {
    let data = {
        $set: {
            hari: req.body.hari,
            buka_jam: req.body.buka_jam,
            tutup_jam: req.body.tutup_jam,
            dokter_id:  require('mongodb').ObjectId(req.body.dokter_id),
            updateAt: util.dateConverter(new Date())
        }
    }
    let clause = {_id: require('mongodb').ObjectId(req.body.id)}
    dataValidate(data.$set, 'update')
    .then(async function() {
        let response = {status: false}

        let result = await dbcon.updateData(config.mongodb.coll_jadwal, clause, data)
        logging.debug(`[updateDataJadwal] >>>> ${JSON.stringify(result)}`)
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
        logging.error(`[updateDataJadwal] >>>> ${JSON.stringify(err.stack)}`)
        let response = util.handleErrorValidation(err)
        res.status(400).send(response)
    })
}

// delete Data
async function deleteDataJadwal(req, res) {
    try {
        let response = {status: false}
        let result = await dbcon.deleteData(config.mongodb.coll_jadwal, {_id: require('mongodb').ObjectId(req.params.id)})
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

async function searchJadwalAutocomplete(req, res) {
    config = iniParser.get()
    try {
        let q = {
            $or: [
                {nama: { $regex: req.params.search, $options: "i" }}
            ]
        }
        let opt = {
            limit: 10,
            sort: {nama: 1} // or use like ==> [["nama",'1']]
        }
        let response = {status: false}

        let result = await dbcon.findData(config.mongodb.coll_jadwal, q, opt)
        logging.debug(`[searchJadwalAutocomplete] >>>> ${JSON.stringify(result)}`)

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
        logging.error(`[searchJadwalAutocomplete] >>>> ${JSON.stringify(e.stack)}`)
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
    findDataJadwal,
    findByIdJadwal,
    findJadwalByIdDokter,
    createDataJadwal,
    updateDataJadwal,
    deleteDataJadwal,
    searchJadwalAutocomplete
};
