import { acceptHMRUpdate, defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import APIService from '@/utils/request'

export const useMainStore = defineStore('MainStore', () => {
    const filters = reactive({
        league_id: null,
        season_id: null,
        week_number: 1,
    })
    const leagues = ref([])
    const seasons = ref([])
    const competition_weeks = ref([])
    const current_week = ref([])
    const current_week_number = ref(1)
    const selected_league = ref(null)
    const teams = ref([])
    const standings = ref([])

    async function submitFilter(){
        await APIService.get(`/leagues`, filters).then((response: any) => {
            leagues.value = response
            selected_league.value = response[0]
            current_week_number.value = response[0].current_week
            competition_weeks.value = response[0].competition_weeks
            if (response[0].competition_weeks){
                current_week.value = response[0].competition_weeks.find(e => e.week_number == current_week_number.value)
            }
            teams.value = response[0].teams
        }).catch(() => {
            console.log("error1")
        })
    }

    async function getData(){
        await APIService.get(`/leagues`, filters).then((response: any) => {
            leagues.value = response
            selected_league.value = response[0]
            filters.league_id = response[0].id
            current_week_number.value = response[0].current_week
            competition_weeks.value = response[0].competition_weeks
            if (response[0].competition_weeks){
                console.log("competitions exists")
                current_week.value = response[0].competition_weeks.find(e => e.week_number == current_week_number.value)
            }
            teams.value = response[0].teams
            getSeasons()
        }).catch(() => {
            console.log("error2")
        })
    }

    async function getSeasons(){
        await APIService.get(`/seasons`, filters).then((response: any) => {
            seasons.value = response
            filters.season_id = response[0].id
            getStandings()
        }).catch(() => {
            console.log("error3")
        })
    }

    async function getStandings(){
            await APIService.get(`/standings`, filters).then((response: any) => {
                standings.value = response
            }).catch(() => {
                console.log("error4")
            })
    }

    async function prepareSchedule(){
        await APIService.post(`/prepare-league-schedule`, filters).then((response: any) => {
            getStandings()
        }).catch(() => {
            console.log("error5")
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
            console.log("error6")
        })
    }

    async function playNextWeek(){
        await APIService.post(`/play-week-by-week`, filters).then((response: any) => {
            getData()
        }).catch(() => {
            console.log("error7")
        })
    }

    async function resetAllData(){
        await APIService.post(`/reset-all-data`, filters).then((response: any) => {
            getData()
        }).catch(() => {
            console.log("erro8")
        })
    }

    return {
        filters,
        leagues,
        seasons,
        standings,
        competition_weeks,
        current_week_number,
        current_week,
        selected_league,
        teams,
        getData,
        getSeasons,
        getStandings,
        prepareSchedule,
        weekMatches,
        playAllWeeks,
        playNextWeek,
        resetAllData,
        submitFilter,
    } as const
})

if (import.meta.hot) import.meta.hot.accept(acceptHMRUpdate(useMainStore, import.meta.hot))