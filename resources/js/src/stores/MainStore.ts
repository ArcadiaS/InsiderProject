import { acceptHMRUpdate, defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import APIService from '@/utils/request'

export const useMainStore = defineStore('MainStore', () => {
    const filters = reactive({
        league_id: null,
        season_id: null,
        week_number: null,
    })
    const leagues = ref([])
    const seasons = ref([])
    const competition_weeks = ref([])
    const current_week = ref([])
    const teams = ref([])
    const standings = ref([])


    async function getData(){
        console.log("test2")
        await APIService.get(`/leagues`, filters).then((response: any) => {
            console.log(response)
            leagues.value = response
            filters.league_id = response[0].id
            filters.week_number = response[0].current_week
            competition_weeks.value = response[0].competition_weeks
            if (response[0].competition_weeks){
                current_week.value = response[0].competition_weeks.find(e => e.week_number == filters.week_number)
            }
            teams.value = response[0].teams
            getSeasons()
        }).catch(() => {
            console.log("error")
        })
    }

    async function getSeasons(){
        await APIService.get(`/seasons`, filters).then((response: any) => {
            console.log(response)
            seasons.value = response
            filters.season_id = response.reverse()[0].id
            getStandings()
        }).catch(() => {
            console.log("error")
        })
    }

    async function getStandings(){
            await APIService.get(`/standings`, filters).then((response: any) => {
                standings.value = response
            }).catch(() => {
                console.log("error")
            })
    }

    async function prepareSchedule(){
        await APIService.post(`/prepare-league-schedule`, filters).then((response: any) => {
            getStandings()
        }).catch(() => {
            console.log("error")
        })
    }

    async function weekMatches(week_number){
        if (competition_weeks.value.find(e => e.week_number == week_number)){
            current_week.value = competition_weeks.value.find(e => e.week_number == week_number)
        }
    }

    async function playAllWeeks(){
        await APIService.post(`/play-all-weeks`, filters).then((response: any) => {
            getData()
        }).catch(() => {
            console.log("error")
        })
    }

    async function playNextWeek(){
        await APIService.post(`/play-week-by-week`, filters).then((response: any) => {
            getData()
        }).catch(() => {
            console.log("error")
        })
    }

    async function resetAllData(){
        await APIService.post(`/reset-all-data`, filters).then((response: any) => {
            getData()
        }).catch(() => {
            console.log("error")
        })
    }

    return {
        filters,
        leagues,
        seasons,
        standings,
        competition_weeks,
        current_week,
        teams,
        getData,
        getSeasons,
        prepareSchedule,
        weekMatches,
        playAllWeeks,
        playNextWeek,
        resetAllData,
    } as const
})

if (import.meta.hot) import.meta.hot.accept(acceptHMRUpdate(useMainStore, import.meta.hot))