const iniParser = require('../libs/iniParser')
const logging = require('../libs/logging')
const mongo = require('../libs/mongo')
var MongoClient = require('mongodb').MongoClient;
var MongoDataTable = require('mongo-datatable');

let config = null

async function saveData(collection, data) {
    return new Promise(function(resolve, reject) {
        mongo.insertOne(collection, data, function (err, result){
            if(err) reject (err)
            resolve({_id:result["ops"][0]["_id"]})
        })
    })
}

function updateData(collection, clause, doc) {
    return new Promise(function(resolve, reject){
        mongo.updateOne(collection, clause, doc, function(err, result){
            if(err) reject(err)
            resolve(result)
        })
    })
}

function deleteData(collection, doc) {
    return new Promise(function(resolve, reject){
        mongo.deleteOne(collection, doc, function(err, result){
            if(err) reject(err)
            resolve(result)
        })
    })
}

function deleteAll(collection, doc) {
    return new Promise(function(resolve, reject){
        mongo.deleteMany(collection, doc, function(err, result){
            if(err) reject(err)
            resolve(result)
        })
    })
}

function findData(collection, doc, option= {}) {
    return new Promise(function(resolve, reject){
        mongo.find(collection, doc, option, function(err, result){
            if(err) reject(err)
            resolve(result)
        })
    })
}

function findLoop(collection, doc) {
    return new Promise(function(resolve, reject){
        mongo.aggregate(collection, doc, function(err, result){
            if(err) reject(err)
            resolve(result)
        })
    })
}

function findLast(collection) {
    return new Promise(function(resolve, reject){
        mongo.findLast(collection, {}, function(err, result){
            if(err) reject(err)
            resolve(result)
        })
    })
}

function findDataById(collection,doc) {
    return new Promise(function(resolve, reject){
        mongo.findOne(collection, doc, function(err, result){
            if(err) reject(err)
            resolve(result)
        })
    })
}

function findDataDatatable(collection, options) {
    return new Promise(function(resolve, reject){
        mongo.findDataDatatable(collection, options, function(err, result){
            if(err) reject(err)
            resolve(result)
        })
    })
}

function checkData(collection, doc) {
    return new Promise(function(resolve, reject){
        mongo.checkData(collection, doc, function(err, result){
            if(err) reject(err)
            resolve(result)
        })
    })
}

module.exports = {
    findData,
    findLoop,
    findDataById,
    findLast,
    findDataDatatable,
    saveData,
    updateData,
    deleteData,
    deleteAll,
    checkData
}
