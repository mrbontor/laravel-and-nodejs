module.exports = function(app) {
    const router_dokter = require('../controllers/controller_dokter');
    const router_spesialis = require('../controllers/controller_dokter_spesialis');
    const router_rumkit = require('../controllers/controller_rumkit');
    const router_skill = require('../controllers/controller_keahlian');
    const router_jadwal = require('../controllers/controller_dokter_jadwal');
    const router_penyakit = require('../controllers/controller_jenis_penyakit');

    /**
     * router dokter
     */
    app.route('/create-dokter')
        .post(router_dokter.createDataDokter)
    app.route('/update-dokter')
        .put(router_dokter.updateDataDokter)
    app.route('/delete-dokter/:id')
        .delete(router_dokter.deleteDataDokter)
    app.route('/list-dokter')
        .get(router_dokter.findDataDokter)
    app.route('/table-dokter')
        .get(router_dokter.findaDataTable)
    app.route('/find-dokter/:id')
        .get(router_dokter.findByIdDokter)
    app.route('/search-dokter/:search')
        .get(router_dokter.searchDokterAutocomplete)
    app.route('/find-by-spesialis/:id')
        .get(router_dokter.findDokterBySpesialis)
    app.route('/find-by-lokasi')
        .post(router_dokter.findDokterByLokasi)
    app.route('/find-by-skill/:id')
        .get(router_dokter.findDokterBySkill)
    app.route('/find-by-speslok')
        .post(router_dokter.findDokterBySpesialisAndLokasi)


    /**
     * router dokter spesialis
     */
    app.route('/create-spesialis')
        .post(router_spesialis.createDataSpesialis)
    app.route('/update-spesialis')
        .put(router_spesialis.updateDataSpesialis)
    app.route('/delete-spesialis/:id')
        .delete(router_spesialis.deleteDataSpesialis)
    app.route('/list-spesialis')
        .get(router_spesialis.findDataSpesialis)
    app.route('/find-spesialis/:id')
        .get(router_spesialis.findByIdSpesialis)
    app.route('/search-spesialis/:search')
        .get(router_spesialis.searchSpesialisAutocomplete)
    app.route('/delete-spesialis/:search')
        .delete(router_spesialis.deleteDataSpesialis)

    /**
     * router rumah sakit /klinik
     */
    app.route('/create-rumkit')
        .post(router_rumkit.createDataRumkit)
    app.route('/update-rumkit')
        .put(router_rumkit.updateDataRumkit)
    app.route('/delete-rumkit/:id')
        .delete(router_rumkit.deleteDataRumkit)
    app.route('/list-rumkit')
        .get(router_rumkit.findDataRumkit)
    app.route('/find-rumkit/:id')
        .get(router_rumkit.findByIdRumkit)
    app.route('/search-rumkit/:search')
        .get(router_rumkit.searchRumkitAutocomplete)
    app.route('/delete-rumkit/:search')
        .delete(router_rumkit.deleteDataRumkit)

    /**
     * router keahlian
     */
    app.route('/create-skill')
        .post(router_skill.createDataKeahlian)
    app.route('/update-skill')
        .put(router_skill.updateDataKeahlian)
    app.route('/delete-skill/:id')
        .delete(router_skill.deleteDataKeahlian)
    app.route('/list-skill')
        .get(router_skill.findDataKeahlian)
    app.route('/find-skill/:id')
        .get(router_skill.findByIdKeahlian)
    app.route('/search-skill/:search')
        .get(router_skill.searchKeahlianAutocomplete)
    app.route('/delete-skill/:search')
        .delete(router_skill.deleteDataKeahlian)

    /**
     * router jadwal dokter
     */
    app.route('/create-jadwal')
        .post(router_jadwal.createDataJadwal)
    app.route('/update-jadwal')
        .put(router_jadwal.updateDataJadwal)
    app.route('/delete-jadwal/:id')
        .delete(router_jadwal.deleteDataJadwal)
    app.route('/list-jadwal')
        .get(router_jadwal.findDataJadwal)
    app.route('/find-jadwal/:id')
        .get(router_jadwal.findByIdJadwal)
    app.route('/find-jadwal-dokter/:id')
        .get(router_jadwal.findJadwalByIdDokter)
    app.route('/search-jadwal/:search')
        .get(router_jadwal.searchJadwalAutocomplete)
    app.route('/delete-jadwal/:search')
        .delete(router_jadwal.deleteDataJadwal)

    /**
     * router dokter penyakit
     */
    app.route('/create-penyakit')
        .post(router_penyakit.createDataPenyakit)
    app.route('/update-penyakit')
        .put(router_penyakit.updateDataPenyakit)
    app.route('/delete-penyakit/:id')
        .delete(router_penyakit.deleteDataPenyakit)
    app.route('/list-penyakit')
        .get(router_penyakit.findDataPenyakit)
    app.route('/find-penyakit/:id')
        .get(router_penyakit.findByIdPenyakit)
    app.route('/search-penyakit/:search')
        .get(router_penyakit.searchPenyakitAutocomplete)
    app.route('/delete-penyakit/:search')
        .delete(router_penyakit.deleteDataPenyakit)

};
