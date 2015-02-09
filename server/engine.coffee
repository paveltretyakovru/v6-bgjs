module.exports =
	initGame: (room) ->
        userData 			= inviteData : room.inviteData
        room.game.bones 	= userData.inviteData.bones 	= @Backgammon.generateBones()		# кости первого хода
        room.game.lotbones 	= userData.inviteData.lotbones 	= @Backgammon.generateLotBones()	# кости жребия
        room.game.pieces 	= userData.inviteData.pieces 	= @Backgammon.generatePieces 30		# ids фишек
        userData    

    setFirst : (room) ->
    	if room.game.lotbones[0] > room.game.lotbones[1]
    		room.players[0]
    	else
    		room.players[1]

	doTurn : (room, user, turn) ->		
		if 'data' of turn
			# если игрок закончил ходить и передает ход другому генерируем кости для следующего игрока
			if turn.data.end				
				#turn.data.bones = [3 , 3]
				turn.data.bones = @Backgammon.generateBones()
		turn
    

	switchPlayer: (room, user, turn) ->
		# need switch
		if turn isnt undefined and 'data' of turn
			if turn.data.end
				if room.players[0] == user
					room.players[1]
				else
					room.players[0]
			# no switch yo!
			else user
		else
			user

	getGameResult: (room, user, turn) ->		

		switch turn.action.result
			# win first player, white
			when 1				
				console.log 'TEST turn result', turn
				winner : user

			#draw
			when 3 then winner : null

			else return false	

	Backgammon :
		generatePieces: (countpieces) ->
			pieces = []
			for i in [0...countpieces]
				pieces[i] = i
			
			pieces

		generateBones : ->
			bones 		= [0 , 0]
			bones[0]	= @randomNumber 1 , 6
			bones[1] 	= @randomNumber 1 , 6
			bones

		generateLotBones : ->
			samples = []
			bones 	= [0 , 0]
			unic 	= false

			bones[0] = @randomNumber 1 , 6
			samples = while not unic
				random = @randomNumber 1 , 6
				if random isnt bones[0]
					unic 		= true
					random

			# Получаем последний элемент массива, остальные не уникальны
			bones[1] = samples[samples.length-1]

			console.log('Сгенерированы лотовые кости: ' , bones)		

			bones

		randomNumber : (min , max) ->
			rand = min - 0.5 + Math.random()*(max-min+1)
			rand = Math.round rand
			rand