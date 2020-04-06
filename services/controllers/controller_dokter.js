const fs = require('fs')
const Ajv = require('ajv');
const iniParser = require('../libs/iniParser')
const logging = require('../libs/logging')
const mongo = require('../libs/mongo')
const util = require('../libs/utils')
const dbcon = require('./dbcontroller')
const MongoDataTable = require('mongo-datatable');
// const mongodb = require('mongodb')
const createTempalte = fs.readFileSync('./data/create_dokter.json', 'utf-8')
const updateTemplate = fs.readFileSync('./data/update_dokter.json', 'utf-8')

//show All error if data not valid
const ajv = new Ajv({
    allErrors: true,
    loopRequired: Infinity
});
// options can be passed, e.g. {allErrors: true}
const validateCreate = ajv.compile(JSON.parse(createTempalte))
const validateUpdate = ajv.compile(JSON.parse(updateTemplate))

let config = iniParser.get()

// list dokter
async function findDataDokter(req, res) {
    try {
        let response = {status: false}

        let list = await dbcon.findLoop(config.mongodb.coll_dokter, util.aggregationDokter())
        logging.debug(`[findDataDokter] >>>> ${JSON.stringify(list)}`)

        if (list.length === 0) {
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
        logging.error(`[findDataDokter] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

// read dataTable
async function findaDataTable(req, res) {
    try {
        let response = {status: false}

        let dataTable = req.query
        dataTable.caseInsensitiveSearch = true
        dataTable.showAlertOnError = true
        // console.log(JSON.stringify(mongo));

        let result = await dbcon.findDataDatatable(config.mongodb.coll_dokter, dataTable)
        logging.debug(`[findaDataTable] >>>> ${JSON.stringify(result)}`)

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
        logging.error(`[findaDataTable] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

//get dokter by id
async function findByIdDokter(req, res) {
    try {
        let response = {status: false}
        // let result = await dbcon.findData(config.mongodb.coll_dokter, {_id: require('mongodb').ObjectId(req.params.id)})
        let getDokter = await dbcon.findLoop(config.mongodb.coll_dokter, util.aggregationDokter(1, {_id: require('mongodb').ObjectId(req.params.id)}))
        logging.debug(`[findByIdDokter] >>>> ${JSON.stringify(getDokter)}`)
        if (getDokter.length > 0) {
            let results = await getJadwalDokter(getDokter)
            logging.debug(`[getJadwal] >>>> ${JSON.stringify(results)}`)
            response = {
                status: true,
                message: 'Successfully load data',
                data: results[0]
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
        logging.error(`[findByIdDokter] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

// create data dokter
function createDataDokter(req, res) {
    let data = {
        no_induk: req.body.no_induk,
        nama: req.body.nama,
        jenkel: req.body.jenkel,
        spesialis: req.body.spesialis,
        lokasi: req.body.lokasi,
        skill: req.body.skill,
        tgl_lahir: req.body.tgl_lahir,
        keterangan: req.body.keterangan,
        status: true,
        createAt: util.dateConverter(new Date()),
        updateAt: util.dateConverter(new Date())
    }
    dataValidate(data, 'create')
    .then(async function() {
        let response = {status: false}
        // console.log(require('mongodb').ObjectId(req.body.spesialis[0]))
        let getDokter = await dbcon.findDataById(config.mongodb.coll_dokter, {no_induk: req.body.no_induk})
        logging.debug(`[isExist] >>>> ${JSON.stringify(getDokter !== null)}`)
        if (getDokter !== null) {
            response.message= `This ${req.body.no_induk} already used`
            response.data= []

            res.status(400).send(response)
            return ;
        }

        let tempSpesialis = []
        let tempSkill = []
        let tempLokasi = []
        req.body.spesialis.map(function (a) {
            console.log(a);
            tempSpesialis.push(require('mongodb').ObjectId(a))
        })

        req.body.skill.map(function (b) {
            console.log(b);
            tempSkill.push(require('mongodb').ObjectId(b))
        })

        req.body.lokasi.map(function (c) {
            console.log(c);
            tempLokasi.push(require('mongodb').ObjectId(c))
        })
        data.spesialis = tempSpesialis
        data.skill = tempSkill
        data.lokasi = tempLokasi

        let result = await dbcon.saveData(config.mongodb.coll_dokter, data)
        logging.debug(`[createDataDokter] >>>> ${JSON.stringify(result)}`)
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
        logging.error(`[createDataDokter] >>>> ${JSON.stringify(err.stack)}`)
        let response = util.handleErrorValidation(err)
        res.status(400).send(response)
    })
}

// update data dokter
function updateDataDokter(req, res) {
    let data = {
        $set: {
            no_induk: req.body.no_induk,
            nama: req.body.nama,
            jenkel: req.body.jenkel,
            spesialis: req.body.spesialis,
            lokasi: req.body.lokasi,
            skill: req.body.skill,
            tgl_lahir: req.body.tgl_lahir,
            keterangan: req.body.keterangan,
            status: req.body.status,
            updateAt: util.dateConverter(new Date())
        }
    }
    let clause = {_id: require('mongodb').ObjectId(req.body.id)}
    dataValidate(data.$set, 'update')
    .then(async function() {
        let response = {status: false}

        let tempSpesialis = []
        let tempSkill = []
        let tempLokasi = []

        for (var i = 0; i < req.body.spesialis.length; i++) {
            tempSpesialis.push({id: require('mongodb').ObjectId(req.body.spesialis[i].id)})
        }
        for (var i = 0; i < req.body.skill.length; i++) {
            tempSkill.push({id: require('mongodb').ObjectId(req.body.skill[i].id)})
        }
        for (var i = 0; i < req.body.lokasi.length; i++) {
            tempLokasi.push({id: require('mongodb').ObjectId(req.body.lokasi[i].id)})
        }

        let result = await dbcon.updateData(config.mongodb.coll_dokter, clause, data)
        logging.debug(`[updateDataDokter] >>>> ${JSON.stringify(result)}`)
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
        logging.error(`[updateDataDokter] >>>> ${JSON.stringify(err.stack)}`)
        let response = util.handleErrorValidation(err)
        res.status(400).send(response)
    })
}

// delete Data
async function deleteDataDokter(req, res) {
    try {
        let response = {status: false}
        let result = await dbcon.deleteData(config.mongodb.coll_dokter, {_id: require('mongodb').ObjectId(req.params.id)})
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

function searchArray(nameKey, myArray) {
    for (var i = 0; i < myArray.length; i++) {
        if (myArray[i].no_induk === nameKey) {
            return true
        } else {
            return false
        }
    }
}

async function searchDokterAutocomplete(req, res) {
    config = iniParser.get()
    try {
        let q = {
            $or: [
                {nama: { $regex: req.params.search, $options: "i" }},
                // {lokasi: { $regex: req.params.search, $options: "i" }},
            ]
        }
        let opt = {
            limit: 10,
            sort: {nama: 1} // or use like ==> [["nama",'1']]
        }
        let response = {status: false}

        let result = await dbcon.findData(config.mongodb.coll_dokter, q, opt)
        logging.debug(`[searchDokterAutocomplete] >>>> ${JSON.stringify(result)}`)

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
        logging.error(`[searchDokterAutocomplete] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No fata found',
            data: []
        }
        res.status(400).send(response)
    }
}


//get dokter by spesialis
async function findDokterBySpesialis(req, res) {
    try {
        let response = {status: false}
        // let data = [
        //     {$match: {
        //         nama: req.body.nama,
        //         spesialis: {$eq: require('mongodb').ObjectId(req.body.spesialis)}
        //     }},
        //     {$project: {
        //         spesialis: {$filter: {
        //             input: "$spesialis",
        //             as: "spesialis",
        //             cond: {$eq: ["$$spesialis", require('mongodb').ObjectId(req.body.id)]}
        //         }}
        //     }},
        //     {
        //         $lookup: {
        //             from: config.mongodb.coll_spesialis,
        //             localField: "spesialis",
        //             foreignField: "_id",
        //             as: "spesialis"
        //         }
        //     },
        //     {$sort: { nama: 1 } },
        //     {$limit : 2},
        // ]

        let match = {
            // nama: req.body.nama,
            spesialis: {$eq: require('mongodb').ObjectId(req.params.id)}
        }
        // let result = await dbcon.findLoop(config.mongodb.coll_dokter, data)
        let result = await dbcon.findLoop(config.mongodb.coll_dokter, util.aggregationDokter(10, match))
        logging.debug(`[findDokterBySpesialis] >>>> ${JSON.stringify(result)}`)
        if (result.length > 0) {
            let getJadwal = await getJadwalDokter(result)
            logging.debug(`[getJadwal] >>>> ${JSON.stringify(getJadwal)}`)

            response = {
                status: true,
                message: 'Successfully load data',
                data: getJadwal
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
        logging.error(`[findDokterBySpesialis] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

//get dokter by lokasi / rumkit
async function findDokterByLokasi(req, res) {
    try {
        let response = {status: false}
        let match = {
            nama: req.body.nama,
            lokasi: {$eq: require('mongodb').ObjectId(req.body.lokasi)}
        }
        let result = await dbcon.findLoop(config.mongodb.coll_dokter, util.aggregationDokter(10, match))
        logging.debug(`[findDokterByLokasi] >>>> ${JSON.stringify(result)}`)
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
        logging.error(`[findDokterByLokasi] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

//get dokter by skill
async function findDokterBySkill(req, res) {
    try {
        let response = {status: false}
        let match = {
            // nama: req.body.nama,
            skill: {$eq: require('mongodb').ObjectId(req.params.id)}
        }
        let result = await dbcon.findLoop(config.mongodb.coll_dokter, util.aggregationDokter(10, match))
        logging.debug(`[findDokterBySkill] >>>> ${JSON.stringify(result)}`)
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
        logging.error(`[findDokterBySkill] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

//get dokter by spesialis
async function findDokterBySpesialisAndLokasi(req, res) {
    try {
        let response = {status: false}

        let match = {
            lokasi: {$eq: require('mongodb').ObjectId(req.body.lokasi)},
            spesialis: {$eq: require('mongodb').ObjectId(req.body.spesialis)}
        }
        let result = await dbcon.findLoop(config.mongodb.coll_dokter, util.aggregationDokter(10, match))
        logging.debug(`[findDokterBySpesialisAndLokasi] >>>> ${JSON.stringify(result)}`)
        if (result.length > 0) {
            let getJadwal = await getJadwalDokter(result)
            logging.debug(`[getJadwal] >>>> ${JSON.stringify(getJadwal)}`)

            response = {
                status: true,
                message: 'Successfully load data',
                data: getJadwal
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
        logging.error(`[findDokterBySpesialisAndLokasi] >>>> ${JSON.stringify(e.stack)}`)
        let response = {
            status: false,
            message: 'No data found',
            data: []
        }
        res.status(400).send(response)
    }
}

function getJadwalDokter(data) {
    let reData = []
    return new Promise(function(resolve, reject) {
        let result = data.map(async function (item) {
            return new Promise(async function(next) {
                let jadwal = await dbcon.findData(config.mongodb.coll_jadwal, {dokter_id: item._id})

                typeof jadwal

                item.jadwal = jadwal
                if (item.jadwal.length > 0) {
                    reData.push(item)
                    // next()
                }
                next()
            })
        })
        Promise.all(result)
        .then( () => {
                logging.debug(`[loopDone] <<<< Done`)
                resolve(reData)
            })
            .catch(e => {
                logging.error(JSON.stringify(e.stack))
                if (e) reject (e)
            })
    });
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
    findDataDokter,
    findaDataTable,
    findByIdDokter,
    createDataDokter,
    updateDataDokter,
    deleteDataDokter,
    searchDokterAutocomplete,
    findDokterBySpesialis,
    findDokterByLokasi,
    findDokterBySkill,
    findDokterBySpesialisAndLokasi
};
