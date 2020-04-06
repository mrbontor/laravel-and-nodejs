const iniParser = require('./iniParser');

let config = iniParser.get()

function isNow() {
    let options = {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    }
    return new Date().toLocaleString('id', options)
}

function dateConverter(date){
    var a = new Date(date);
    var year = a.getFullYear()
    var month = ("0" + (a.getMonth() + 1)).slice(-2)
    var date = ("0" + a.getDate()).slice(-2)
    var hour = ("0" + a.getHours()).slice(-2)
    var min = ("0" + a.getMinutes()).slice(-2)
    var sec = ("0" + a.getSeconds()).slice(-2)
    var time = year + '-' + month + '-' + date + ' ' + hour + ':' + min + ':' + sec ;
    return time;
}

function handleErrorValidation(error) {
    let response = {
        status: false,
        message: []
    }

    // if (error === 'undefined') {
    //     return response.message = "Something error";
    // }
    for (var i = 0; i < error.length; i++) {
        let obj = {
            type: error[i].dataPath.slice(1),
            message: error[i].message
        }
        response.message.push(obj)
    }

    return response;
}

function aggregationDokter(opt1 = 100, opt2 = {}) {
    return [
        {$match: opt2},
        {
            $lookup: {
                from: config.mongodb.coll_spesialis,
                localField: "spesialis",
                foreignField: "_id",
                as: "spesialis"
            }
        },
        {
            $lookup: {
                from: config.mongodb.coll_jenis_penyakit,
                localField: "spesialis.spesialis_id",
                foreignField: "_id",
                as: "penyakit"
            }
        },
        {
            $lookup: {
                from: config.mongodb.coll_rumkit,
                localField: "lokasi",
                foreignField: "_id",
                as: "lokasi"
            }
        },
        {
            $lookup: {
                from: config.mongodb.coll_keahlian,
                localField: "skill",
                foreignField: "_id",
                as: "skill"
            }
        },
        { $sort: { "nama": 1 } },
        {$limit: opt1},
    ];
}

module.exports = {
    dateConverter,
    handleErrorValidation,
    aggregationDokter
}
