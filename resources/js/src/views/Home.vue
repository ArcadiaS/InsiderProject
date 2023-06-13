<template>
  <div>
      <el-container style="text-align: center">
        <el-header>Header</el-header>
        <el-main>
          <el-card class="box-card">
            <template #header>
              <div class="card-header">
                <span>League Name</span>
                <el-row>
                  <el-col :span="4">
                    <el-select v-model="mainStore.filters.league_id" class="m-2" placeholder="Select" size="large"
                               @change="selectLeague">
                      <el-option
                          v-for="item in mainStore.leagues"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                      />
                    </el-select>
                  </el-col>
                  <el-col :span="4">
                    <el-select v-model="mainStore.filters.season_id" class="m-2" placeholder="Select" size="large">
                      <el-option
                          v-for="item in mainStore.seasons"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                      />
                    </el-select>
                  </el-col>
                  <el-col :span="4">
                    <el-select v-model="mainStore.filters.week_number" class="m-2" placeholder="Select" size="large">
                      <el-option
                          v-for="item in mainStore.competition_weeks"
                          :key="item.id"
                          :label="item.week_number"
                          :value="item.week_number"
                      />
                    </el-select>
                  </el-col>
                  <el-col :span="4">
                    <el-button class="button" @click="prepareSchedule" type="info">Prepare Schedule</el-button>
                  </el-col>
                </el-row>
              </div>
            </template>
            <el-row>
              <el-col :span="18">
                <el-table
                    :data="mainStore.standings"
                    stripe
                    style="width: 100%"
                    highlight-current-row
                    v-if="mainStore.standings">
                  <el-table-column type="index" width="40" />
                  <el-table-column prop="team.name" label="Team Name" width="180" />
                  <el-table-column prop="played" label="Played" />
                  <el-table-column prop="won" label="Won" />
                  <el-table-column prop="drawn" label="Drawn" />
                  <el-table-column prop="lost" label="Lost" />
                  <el-table-column prop="goals_for" label="GF" />
                  <el-table-column prop="goals_against" label="GA" />
                  <el-table-column prop="goal_difference" label="GD" />
                  <el-table-column prop="points" label="Points" />
                  <el-table-column label="Champ. Chance">
                    <template #default="scope">
                      <span class="text-md font-medium">%{{ scope.row.championship_chance }}</span>
                    </template>
                  </el-table-column>
                </el-table>
              </el-col>
              <el-col :span="6">
                <el-card class="box-card">
                  <template #header>
                    <div class="card-header">
                      <el-row>
                        <el-col :span="3"><el-button @click="prevWeek">Prev</el-button></el-col>
                        <el-col :span="15">Week {{mainStore.current_week.week_number}}</el-col>
                        <el-col :span="3"><el-button @click="nextWeek">Next</el-button></el-col>
                      </el-row>
                    </div>
                  </template>
                  <el-row v-for="item in mainStore.current_week.competitions">
                    <el-col :span="8">{{item.home_team.name}}</el-col>
                    <el-col :span="2">{{item.home_team_goals}}</el-col>
                    <el-col :span="2">{{item.away_team_goals}}</el-col>
                    <el-col :span="8">{{item.away_team.name}}</el-col>
                  </el-row>
                  <el-divider />
                  <el-row>
                    <el-col :span="8" @click="PlayAllWeeks" type="primary">Play All Weeks</el-col>
                    <el-col :span="8" @click="PlayNextWeek"  type="primary">Play Next Week</el-col>
                    <el-col :span="8" @click="ResetAllData" type="danger">Reset Data</el-col>
                  </el-row>
                </el-card>
              </el-col>
            </el-row>
          </el-card>
        </el-main>
      </el-container>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useMainStore } from '@/stores/MainStore.ts'

const mainStore = useMainStore()

const PlayAllWeeks = () => {
  mainStore.playAllWeeks()
}
const PlayNextWeek = () => {
  mainStore.playNextWeek()

}
const ResetAllData = () => {
  mainStore.resetAllData()

}

const selectLeague = (selectedLeague) => {
  console.log("selected league", selectedLeague)
  mainStore.filters.league_id = selectedLeague
  mainStore.getSeasons()
}

const prevWeek = () => {
  mainStore.weekMatches(mainStore.current_week.week_number-1)
}
const nextWeek = () => {
  mainStore.weekMatches(mainStore.current_week.week_number+1)
}

const prepareSchedule = () => {
  mainStore.prepareSchedule()
}

onMounted(() => {
  mainStore.getData([])
})

</script>