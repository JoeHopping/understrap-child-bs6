import { spawn } from 'node:child_process'
import { watch } from 'chokidar'

const npmCli = process.env.npm_execpath
if (!npmCli) {
	throw new Error('Run this watcher with `npm run watch`.')
}

const activeBuilds = new Map()
const scheduledBuilds = new Map()

function startBuild(task) {
	const build = { pending: false }
	activeBuilds.set(task, build)
	const child = spawn(process.execPath, [npmCli, 'run', task], { stdio: 'inherit' })

	child.on('error', (error) => {
		console.error(`${task} build could not start:`, error)
	})

	child.on('close', (code) => {
		if (activeBuilds.get(task) !== build) {
			return
		}

		activeBuilds.delete(task)
		if (code !== 0) {
			console.error(`${task} build exited with code ${code}`)
		}
		if (build.pending) {
			startBuild(task)
		}
	})
}

function scheduleBuild(task, event, filePath) {
	console.log(`${event}: ${filePath}; scheduling ${task} build`)
	clearTimeout(scheduledBuilds.get(task))
	scheduledBuilds.set(task, setTimeout(() => {
		scheduledBuilds.delete(task)
		const activeBuild = activeBuilds.get(task)
		if (activeBuild) {
			activeBuild.pending = true
			return
		}
		startBuild(task)
	}, 150))
}

const sassWatcher = watch('src/sass', { ignoreInitial: true })
const jsWatcher = watch('src/js', { ignoreInitial: true })

sassWatcher.on('all', (event, filePath) => scheduleBuild('css', event, filePath))
jsWatcher.on('all', (event, filePath) => scheduleBuild('js', event, filePath))

sassWatcher.on('error', (error) => console.error('Sass watcher error:', error))
jsWatcher.on('error', (error) => console.error('JavaScript watcher error:', error))

console.log('Watching src/sass and src/js for changes.')