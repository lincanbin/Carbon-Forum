/*
 * Carbon-Forum
 * https://github.com/lincanbin/Carbon-Forum
 *
 * Copyright 2015, Lin Canbin
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A high performance open-source forum software written in PHP. 
 */

/**
 * Created with JetBrains PhpStorm.
 * User: taoqili
 * Last Modified: Lin Canbin
 * Date: 2014-12-03
 * To change this template use File | Settings | File Templates.
 */

var Lang = {
	'Title_Can_Not_Be_Empty':'標題不能為空！',
	'Title_Too_Long':'標題長度不能超過{{MaxTitleChars}}位元組，當前標題長度為{{Current_Title_Length}}個位元組',
	'Tags_Empty':'話題不能為空！',
	'Submitting':' 發表中 ',
	'Submit_Success':' 發表成功 ',
	'Submit_Failure':'發表失敗，請再次提交！',
	'Submit_Again':' 再次提交 ',
	'Tags_Too_Much':'最多只能插入{{MaxTagNum}}個話題！',
	'Add_Tags':'添加話題(按Enter添加)',

	'Edit':' 提 交 ',
	'Cancel':' 取 消 ',

	'Confirm_Operation':'確認執行該操作？',
	'Confirm':'確定',

	'Reply':' 回 複 ',
	'Content_Empty':'內容不能為空！',
	'Replying':' 回復中…… ',
	'Reply_Success':' 回復成功 ',
	'Reply_To':'回復'
};

UE.I18N['zh-tw'] = {
	'labelMap':{
		'anchor':'錨點', 'undo':'撤銷', 'redo':'重做', 'bold':'加粗', 'indent':'首行縮進', 'snapscreen':'截圖',
		'italic':'斜體', 'underline':'底線', 'strikethrough':'刪除線', 'subscript':'下標','fontborder':'字元邊框',
		'superscript':'上標', 'formatmatch':'格式刷', 'source':'原始程式碼', 'blockquote':'引用',
		'pasteplain':'純文字粘貼模式', 'selectall':'全選', 'print':'列印', 'preview':'預覽',
		'horizontal':'分隔線', 'removeformat':'清除格式', 'time':'時間', 'date':'日期',
		'unlink':'取消連結', 'insertrow':'前插入行', 'insertcol':'前插入列', 'mergeright':'右合併儲存格', 'mergedown':'下合併儲存格',
		'deleterow':'刪除行', 'deletecol':'刪除列', 'splittorows':'拆分成行',
		'splittocols':'拆分成列', 'splittocells':'完全拆分儲存格','deletecaption':'刪除表格標題','inserttitle':'插入標題',
		'mergecells':'合併多個儲存格', 'deletetable':'刪除表格', 'cleardoc':'清空文檔','insertparagraphbeforetable':"表格前插入行",'insertcode':'代碼語言',
		'fontfamily':'字體', 'fontsize':'字型大小', 'paragraph':'段落格式', 'simpleupload':'單圖上傳', 'insertimage':'多圖上傳','edittable':'表格屬性','edittd':'儲存格屬性', 'link':'超連結',
		'emotion':'表情', 'spechars':'特殊字元', 'searchreplace':'查詢替換', 'map':'Baidu地圖', 'gmap':'Google地圖',
		'insertvideo':'視頻', 'help':'幫助', 'justifyleft':'居左對齊', 'justifyright':'居右對齊', 'justifycenter':'居中對齊',
		'justifyjustify':'兩端對齊', 'forecolor':'字體顏色', 'backcolor':'背景色', 'insertorderedlist':'有序列表',
		'insertunorderedlist':'無序列表', 'fullscreen':'全屏', 'directionalityltr':'從左向右輸入', 'directionalityrtl':'從右向左輸入',
		'rowspacingtop':'段前距', 'rowspacingbottom':'段後距',  'pagebreak':'分頁', 'insertframe':'插入Iframe', 'imagenone':'默認',
		'imageleft':'左浮動', 'imageright':'右浮動', 'attachment':'附件', 'imagecenter':'居中', 'wordimage':'圖片轉存',
		'lineheight':'行間距','edittip' :'編輯提示','customstyle':'自訂標題', 'autotypeset':'自動排版',
		'webapp':'百度應用','touppercase':'字母大寫', 'tolowercase':'字母小寫','background':'背景','template':'範本','scrawl':'塗鴉',
		'music':'音樂','inserttable':'插入表格','drafts': '從草稿箱載入', 'charts': '圖表'
	},
	'insertorderedlist':{
		'num':'1,2,3...',
		'num1':'1),2),3)...',
		'num2':'(1),(2),(3)...',
		'cn':'一,二,三....',
		'cn1':'一),二),三)....',
		'cn2':'(一),(二),(三)....',
		'decimal':'1,2,3...',
		'lower-alpha':'a,b,c...',
		'lower-roman':'i,ii,iii...',
		'upper-alpha':'A,B,C...',
		'upper-roman':'I,II,III...'
	},
	'insertunorderedlist':{
		'circle':'○ 大圓圈',
		'disc':'● 小黑點',
		'square':'■ 小方塊 ',
		'dash' :'— 破折號',
		'dot':' 。 小圓圈'
	},
	'paragraph':{'p':'段落', 'h1':'標題 1', 'h2':'標題 2', 'h3':'標題 3', 'h4':'標題 4', 'h5':'標題 5', 'h6':'標題 6'},
	'fontfamily':{
		'songti':'宋體',
		'kaiti':'楷體',
		'heiti':'黑體',
		'lishu':'隸書',
		'yahei':'微軟雅黑',
		'andaleMono':'andale mono',
		'arial': 'arial',
		'arialBlack':'arial black',
		'comicSansMs':'comic sans ms',
		'impact':'impact',
		'timesNewRoman':'times new roman'
	},
	'customstyle':{
		'tc':'標題居中',
		'tl':'標題居左',
		'im':'強調',
		'hi':'明顯強調'
	},
	'autoupload': {
		'exceedSizeError': '檔大小超出限制',
		'exceedTypeError': '檔案格式不允許',
		'jsonEncodeError': '伺服器返回格式錯誤',
		'loading':"正在上傳...",
		'loadError':"上傳錯誤",
		'errorLoadConfig': '後端配置項沒有正常載入，上傳外掛程式不能正常使用！'
	},
	'simpleupload':{
		'exceedSizeError': '檔大小超出限制',
		'exceedTypeError': '檔案格式不允許',
		'jsonEncodeError': '伺服器返回格式錯誤',
		'loading':"正在上傳...",
		'loadError':"上傳錯誤",
		'errorLoadConfig': '後端配置項沒有正常載入，上傳外掛程式不能正常使用！'
	},
	'elementPathTip':"元素路徑",
	'wordCountTip':"字數統計",
	'wordCountMsg':'當前已輸入{#count}個字元, 您還可以輸入{#leave}個字元。 ',
	'wordOverFlowMsg':'<span style="color:red;">字數超出最大允許值，伺服器可能拒絕保存！</span>',
	'ok':"確認",
	'cancel':"取消",
	'closeDialog':"關閉對話方塊",
	'tableDrag':"表格拖動必須引入uiUtils.js文件！",
	'autofloatMsg':"工具列浮動依賴編輯器UI，您首先需要引入UI檔!",
	'loadconfigError': '獲取後臺配置項請求出錯，上傳功能將不能正常使用！',
	'loadconfigFormatError': '後臺配置項返回格式出錯，上傳功能將不能正常使用！',
	'loadconfigHttpError': '請求後臺配置項http錯誤，上傳功能將不能正常使用！',
	'snapScreen_plugin':{
		'browserMsg':"僅支持IE流覽器！",
		'callBackErrorMsg':"伺服器返回資料有誤，請檢查配置項之後重試。",
		'uploadErrorMsg':"截圖上傳失敗，請檢查伺服器端環境! "
	},
	'insertcode':{
		'as3':'ActionScript 3',
		'bash':'Bash/Shell',
		'cpp':'C/C++',
		'css':'CSS',
		'cf':'ColdFusion',
		'c#':'C#',
		'delphi':'Delphi',
		'diff':'Diff',
		'erlang':'Erlang',
		'groovy':'Groovy',
		'html':'HTML',
		'java':'Java',
		'jfx':'JavaFX',
		'js':'JavaScript',
		'pl':'Perl',
		'php':'PHP',
		'plain':'Plain Text',
		'ps':'PowerShell',
		'python':'Python',
		'ruby':'Ruby',
		'scala':'Scala',
		'sql':'SQL',
		'vb':'Visual Basic',
		'xml':'XML'
	},
	'confirmClear':"確定清空當前文檔麼？",
	'contextMenu':{
		'delete':"刪除",
		'selectall':"全選",
		'deletecode':"刪除代碼",
		'cleardoc':"清空文檔",
		'confirmclear':"確定清空當前文檔麼？",
		'unlink':"刪除超連結",
		'paragraph':"段落格式",
		'edittable':"表格屬性",
		'aligntd':"儲存格對齊方式",
		'aligntable':'表格對齊方式',
		'tableleft':'左浮動',
		'tablecenter':'居中顯示',
		'tableright':'右浮動',
		'edittd':"儲存格屬性",
		'setbordervisible':'設定表格邊線可見',
		'justifyleft':'左對齊',
		'justifyright':'右對齊',
		'justifycenter':'居中對齊',
		'justifyjustify':'兩端對齊',
		'table':"表格",
		'inserttable':'插入表格',
		'deletetable':"刪除表格",
		'insertparagraphbefore':"前插入段落",
		'insertparagraphafter':'後插入段落',
		'deleterow':"刪除當前行",
		'deletecol':"刪除當前列",
		'insertrow':"前插入行",
		'insertcol':"左插入列",
		'insertrownext':'後插入行',
		'insertcolnext':'右插入列',
		'insertcaption':'插入表格名稱',
		'deletecaption':'刪除表格名稱',
		'inserttitle':'插入表格標題行',
		'deletetitle':'刪除表格標題行',
		'inserttitlecol':'插入表格標題列',
		'deletetitlecol':'刪除表格標題列',
		'averageDiseRow':'平均分佈各行',
		'averageDisCol':'平均分佈各列',
		'mergeright':"向右合併",
		'mergeleft':"向左合併",
		'mergedown':"向下合併",
		'mergecells':"合併儲存格",
		'splittocells':"完全拆分儲存格",
		'splittocols':"拆分成列",
		'splittorows':"拆分成行",
		'tablesort':'表格排序',
		'enablesort':'設定表格可排序',
		'disablesort':'取消表格可排序',
		'reversecurrent':'逆序當前',
		'orderbyasc':'按ASCII字元昇冪',
		'reversebyasc':'按ASCII字元降冪',
		'orderbynum':'按數值大小昇冪',
		'reversebynum':'按數值大小降冪',
		'borderbk':'邊框網底',
		'setcolor':'表格隔行變色',
		'unsetcolor':'取消表格隔行變色',
		'setbackground':'選區背景隔行',
		'unsetbackground':'取消選區背景',
		'redandblue':'紅藍相間',
		'threecolorgradient':'三色漸變',
		'copy':"複製(Ctrl + c)",
		'copymsg': "流覽器不支援,請使用 'Ctrl + c'",
		'paste':"粘貼(Ctrl + v)",
		 'pastemsg': "流覽器不支援,請使用 'Ctrl + v'"
	},
	'copymsg': "流覽器不支援,請使用 'Ctrl + c'",
	'pastemsg': "流覽器不支援,請使用 'Ctrl + v'",
	'anthorMsg':"連結",
	'clearColor':'清空顏色',
	'standardColor':'標準顏色',
	'themeColor':'主題顏色',
	'property':'屬性',
	'default':'默認',
	'modify':'修改',
	'justifyleft':'左對齊',
	'justifyright':'右對齊',
	'justifycenter':'居中',
	'justify':'默認',
	'clear':'清除',
	'anchorMsg':'錨點',
	'delete':'刪除',
	'clickToUpload':"點擊上傳",
	'unset':'尚未設定語言檔',
	't_row':'行',
	't_col':'列',
	'more':'更多',
	'pasteOpt':'粘貼選項',
	'pasteSourceFormat':"保留源格式",
	'tagFormat':'只保留標籤',
	'pasteTextFormat':'只保留文本',
	'autoTypeSet':{
		'mergeLine':"合併空行",
		'delLine':"清除空行",
		'removeFormat':"清除格式",
		'indent':"首行縮進",
		'alignment':"對齊方式",
		'imageFloat':"圖片浮動",
		'removeFontsize':"清除字型大小",
		'removeFontFamily':"清除字體",
		'removeHtml':"清除冗餘HTML代碼",
		'pasteFilter':"粘貼過濾",
		'run':"執行",
		'symbol':'符號轉換',
		'bdc2sb':'全形轉半形',
		'tobdc':'半形轉全形'
	},

	'background':{
		'static':{
			'lang_background_normal':'背景設定',
			'lang_background_local':'線上圖片',
			'lang_background_set':'選項',
			'lang_background_none':'無背景色',
			'lang_background_colored':'有背景色',
			'lang_background_color':'顏色設定',
			'lang_background_netimg':'網路圖片',
			'lang_background_align':'對齊方式',
			'lang_background_position':'精確定位',
			'repeatType':{'options':["居中", "橫向重複", "縱向重複", "平鋪","自訂"]}

		},
		'noUploadImage':"當前未上傳過任何圖片！",
		'toggleSelect':"按一下可切換選中狀態\n原圖尺寸: "
	},
	//===============dialog i18N=======================
	'insertimage':{
		'static':{
			'lang_tab_remote':"插入圖片", //節點
			'lang_tab_upload':"本地上傳",
			'lang_tab_online':"線上管理",
			'lang_tab_search':"圖片搜索",
			'lang_input_url':"地 址：",
			'lang_input_size':"大 小：",
			'lang_input_width':"寬度",
			'lang_input_height':"高度",
			'lang_input_border':"邊 框：",
			'lang_input_vhspace':"邊 距：",
			'lang_input_title':"描 述：",
			'lang_input_align':'圖片浮動方式：',
			'lang_imgLoading':"　圖片載入中……",
			'lang_start_upload':"開始上傳",
			'lock':{'title':"鎖定寬高比例"}, //屬性
			'searchType':{'title':"圖片類型", 'options':["新聞", "壁紙", "表情", "頭像"]}, //select的option
			'searchTxt':{'value':"請輸入搜索關鍵字"},
			'searchBtn':{'value':"百度一下"},
			'searchReset':{'value':"清空搜索"},
			'noneAlign':{'title':'無浮動'},
			'leftAlign':{'title':'左浮動'},
			'rightAlign':{'title':'右浮動'},
			'centerAlign':{'title':'居中獨佔一行'}
		},
		'uploadSelectFile':'點擊選擇圖片',
		'uploadAddFile':'繼續添加',
		'uploadStart':'開始上傳',
		'uploadPause':'暫停上傳',
		'uploadContinue':'繼續上傳',
		'uploadRetry':'重試上傳',
		'uploadDelete':'刪除',
		'uploadTurnLeft':'向左旋轉',
		'uploadTurnRight':'向右旋轉',
		'uploadPreview':'預覽中',
		'uploadNoPreview':'不能預覽',
		'updateStatusReady': '選中_張圖片，共_KB。',
		'updateStatusConfirm': '已成功上傳_張照片，_張照片上傳失敗',
		'updateStatusFinish': '共_張（_KB），_張上傳成功',
		'updateStatusError': '，_張上傳失敗。',
		'errorNotSupport': 'WebUploader 不支持您的流覽器！如果你使用的是IE流覽器，請嘗試升級 flash 播放機。',
		'errorLoadConfig': '後端配置項沒有正常載入，上傳外掛程式不能正常使用！',
		'errorExceedSize':'文件大小超出',
		'errorFileType':'檔案格式不允許',
		'errorInterrupt':'檔案傳輸中斷',
		'errorUploadRetry':'上傳失敗，請重試',
		'errorHttp':'http請求錯誤',
		'errorServerUpload':'伺服器返回出錯',
		'remoteLockError':"寬高不正確,不能所定比例",
		'numError':"請輸入正確的長度或者寬度值！例如：123，400",
		'imageUrlError':"不允許的圖片格式或者圖片域！",
		'imageLoadError':"圖片載入失敗！請檢查連結位址或網路狀態！",
		'searchRemind':"請輸入搜索關鍵字",
		'searchLoading':"圖片載入中，請稍後……",
		'searchRetry':" :( ，抱歉，沒有找到圖片！請重試一次！"
	},
	'attachment':{
		'static':{
			'lang_tab_upload': '上傳附件',
			'lang_tab_online': '線上附件',
			'lang_start_upload':"開始上傳",
			'lang_drop_remind':"可以將檔拖到這裡，單次最多可選100個文件"
		},
		'uploadSelectFile':'點擊選擇檔',
		'uploadAddFile':'繼續添加',
		'uploadStart':'開始上傳',
		'uploadPause':'暫停上傳',
		'uploadContinue':'繼續上傳',
		'uploadRetry':'重試上傳',
		'uploadDelete':'刪除',
		'uploadTurnLeft':'向左旋轉',
		'uploadTurnRight':'向右旋轉',
		'uploadPreview':'預覽中',
		'updateStatusReady': '選中_個文件，共_KB。',
		'updateStatusConfirm': '已成功上傳_個檔，_個檔上傳失敗',
		'updateStatusFinish': '共_個（_KB），_個上傳成功',
		'updateStatusError': '，_張上傳失敗。',
		'errorNotSupport': 'WebUploader 不支持您的流覽器！如果你使用的是IE流覽器，請嘗試升級 flash 播放機。',
		'errorLoadConfig': '後端配置項沒有正常載入，上傳外掛程式不能正常使用！',
		'errorExceedSize':'文件大小超出',
		'errorFileType':'檔案格式不允許',
		'errorInterrupt':'檔案傳輸中斷',
		'errorUploadRetry':'上傳失敗，請重試',
		'errorHttp':'http請求錯誤',
		'errorServerUpload':'伺服器返回出錯'
	},
	'insertvideo':{
		'static':{
			'lang_tab_insertV':"插入視頻",
			'lang_tab_searchV':"搜索視頻",
			'lang_tab_uploadV':"上傳視頻",
			'lang_video_url':"視頻網址",
			'lang_video_size':"視頻尺寸",
			'lang_videoW':"寬度",
			'lang_videoH':"高度",
			'lang_alignment':"對齊方式",
			'videoSearchTxt':{'value':"請輸入搜索關鍵字！"},
			'videoType':{'options':["全部", "熱門", "娛樂", "搞笑", "體育", "科技", "綜藝"]},
			'videoSearchBtn':{'value':"百度一下"},
			'videoSearchReset':{'value':"清空結果"},

			'lang_input_fileStatus':' 當前未上傳文件',
			'startUpload':{'style':"background:url(upload.png) no-repeat;"},

			'lang_upload_size':"視頻尺寸",
			'lang_upload_width':"寬度",
			'lang_upload_height':"高度",
			'lang_upload_alignment':"對齊方式",
			'lang_format_advice':"建議使用mp4格式."

		},
		'numError':"請輸入正確的數值，如123,400",
		'floatLeft':"左浮動",
		'floatRight':"右浮動",
		'"default"':"默認",
		'block':"獨佔一行",
		'urlError':"輸入的視頻位址有誤，請檢查後再試！",
		'loading':" &nbsp;視頻載入中，請等待……",
		'clickToSelect':"點擊選中",
		'goToSource':'訪問源視頻',
		'noVideo':" &nbsp; &nbsp;抱歉，找不到對應的視頻，請重試！",

		'browseFiles':'流覽檔',
		'uploadSuccess':'上傳成功!',
		'delSuccessFile':'從成功佇列中移除',
		'delFailSaveFile':'移除保存失敗檔',
		'statusPrompt':' 個文件已上傳！ ',
		'flashVersionError':'當前Flash版本過低，請更新FlashPlayer後重試！',
		'flashLoadingError':'Flash載入失敗!請檢查路徑或網路狀態',
		'fileUploadReady':'等待上傳……',
		'delUploadQueue':'從上傳佇列中移除',
		'limitPrompt1':'單次不能選擇超過',
		'limitPrompt2':'個文件！請重新選擇！',
		'delFailFile':'移除失敗檔',
		'fileSizeLimit':'檔大小超出限制！',
		'emptyFile':'空文件無法上傳！',
		'fileTypeError':'檔案類型不允許！',
		'unknownError':'未知錯誤！',
		'fileUploading':'上傳中，請等待……',
		'cancelUpload':'取消上傳',
		'netError':'網路錯誤',
		'failUpload':'上傳失敗!',
		'serverIOError':'伺服器IO錯誤！',
		'noAuthority':'無許可權！',
		'fileNumLimit':'上傳個數限制',
		'failCheck':'驗證失敗，本次上傳被跳過！',
		'fileCanceling':'取消中，請等待……',
		'stopUploading':'上傳已停止……',

		'uploadSelectFile':'點擊選擇檔',
		'uploadAddFile':'繼續添加',
		'uploadStart':'開始上傳',
		'uploadPause':'暫停上傳',
		'uploadContinue':'繼續上傳',
		'uploadRetry':'重試上傳',
		'uploadDelete':'刪除',
		'uploadTurnLeft':'向左旋轉',
		'uploadTurnRight':'向右旋轉',
		'uploadPreview':'預覽中',
		'updateStatusReady': '選中_個文件，共_KB。',
		'updateStatusConfirm': '成功上傳_個，_個失敗',
		'updateStatusFinish': '共_個(_KB)，_個成功上傳',
		'updateStatusError': '，_張上傳失敗。',
		'errorNotSupport': 'WebUploader 不支持您的流覽器！如果你使用的是IE流覽器，請嘗試升級 flash 播放機。',
		'errorLoadConfig': '後端配置項沒有正常載入，上傳外掛程式不能正常使用！',
		'errorExceedSize':'文件大小超出',
		'errorFileType':'檔案格式不允許',
		'errorInterrupt':'檔案傳輸中斷',
		'errorUploadRetry':'上傳失敗，請重試',
		'errorHttp':'http請求錯誤',
		'errorServerUpload':'伺服器返回出錯'
	},
	'webapp':{
		'tip1':"本功能由百度APP提供，如看到此頁面，請各位站長首先申請百度APPKey!",
		'tip2':"申請完成之後請至ueditor.config.js中配置獲得的appkey! ",
		'applyFor':"點此申請",
		'anthorApi':"百度API"
	},
	'template':{
		'static':{
			'lang_template_bkcolor':'背景顏色',
			'lang_template_clear' : '保留原有內容',
			'lang_template_select' : '選擇範本'
		},
		'blank':"空白文檔",
		'blog':"博客文章",
		'resume':"個人簡歷",
		'richText':"圖文混排",
		'sciPapers':"科技論文"


	},
	'scrawl':{
		'static':{
			'lang_input_previousStep':"上一步",
			'lang_input_nextsStep':"下一步",
			'lang_input_clear':'清空',
			'lang_input_addPic':'添加背景',
			'lang_input_ScalePic':'縮放背景',
			'lang_input_removePic':'刪除背景',
			'J_imgTxt':{title:'添加背景圖片'}
		},
		'noScarwl':"尚未作畫，白紙一張~",
		'scrawlUpLoading':"塗鴉上傳中,別急哦~",
		'continueBtn':"繼續",
		'imageError':"糟糕，圖片讀取失敗了！",
		'backgroundUploading':'背景圖片上傳中,別急哦~'
	},
	'music':{
		'static':{
			'lang_input_tips':"輸入歌手/歌曲/專輯，搜索您感興趣的音樂！",
			'J_searchBtn':{value:'搜索歌曲'}
		},
		'emptyTxt':'未搜索到相關音樂結果，請換一個關鍵字試試。',
		'chapter':'歌曲',
		'singer':'歌手',
		'special':'專輯',
		'listenTest':'試聽'
	},
	'anchor':{
		'static':{
			'lang_input_anchorName':'錨點名字：'
		}
	},
	'charts':{
		'static':{
			'lang_data_source':'資料來源：',
			'lang_chart_format': '圖表格式：',
			'lang_data_align': '資料對齊方式',
			'lang_chart_align_same': '資料來源與圖表X軸Y軸一致',
			'lang_chart_align_reverse': '資料來源與圖表X軸Y軸相反',
			'lang_chart_title': '圖表標題',
			'lang_chart_main_title': '主標題：',
			'lang_chart_sub_title': '子標題：',
			'lang_chart_x_title': 'X軸標題：',
			'lang_chart_y_title': 'Y軸標題：',
			'lang_chart_tip': '提示文字',
			'lang_cahrt_tip_prefix': '提示文字首碼：',
			'lang_cahrt_tip_description': '僅圓形圖有效， 當滑鼠移動到圓形圖中相應的塊上時，提示框內的文字的首碼',
			'lang_chart_data_unit': '資料單位',
			'lang_chart_data_unit_title': '單位：',
			'lang_chart_data_unit_description': '顯示在每個數據點上的資料的單位， 比如： 溫度的單位 ℃',
			'lang_chart_type': '圖表類型：',
			'lang_prev_btn': '上一個',
			'lang_next_btn': '下一個'
		}
	},
	'emotion':{
		'static':{
			'lang_input_choice':'泡泡'
		}
	},
	'gmap':{
		'static':{
			'lang_input_address':'地址',
			'lang_input_search':'搜索',
			'address':{value:"北京"}
		},
		searchError:'無法定位到該地址!'
	},
	'help':{
		'static':{
			'lang_input_about':'關於UEditor',
			'lang_input_shortcuts':'快速鍵',
			'lang_input_introduction':'UEditor是由百度web前端研發部開發的所見即所得富文本web編輯器，具有輕量，可定制，注重用戶體驗等特點。開源基於BSD協定，允許自由使用和修改代碼。',
			'lang_Txt_shortcuts':'快速鍵',
			'lang_Txt_func':'功能',
			'lang_Txt_bold':'給選中字設定為加粗',
			'lang_Txt_copy':'複製選中內容',
			'lang_Txt_cut':'剪切選中內容',
			'lang_Txt_Paste':'粘貼',
			'lang_Txt_undo':'重新執行上次操作',
			'lang_Txt_redo':'撤銷上一次操作',
			'lang_Txt_italic':'給選中字設定為斜體',
			'lang_Txt_underline':'給選中字加底線',
			'lang_Txt_selectAll':'全部選中',
			'lang_Txt_visualEnter':'軟回車',
			'lang_Txt_fullscreen':'全屏'
		}
	},
	'insertframe':{
		'static':{
			'lang_input_address':'地址：',
			'lang_input_width':'寬度：',
			'lang_input_height':'高度：',
			'lang_input_isScroll':'允許捲軸：',
			'lang_input_frameborder':'顯示框架邊框：',
			'lang_input_alignMode':'對齊方式：',
			'align':{title:"對齊方式", options:["默認", "左對齊", "右對齊", "居中"]}
		},
		'enterAddress':'請輸入位址!'
	},
	'link':{
		'static':{
			'lang_input_text':'文本內容：',
			'lang_input_url':'連結位址：',
			'lang_input_title':'標題：',
			'lang_input_target':'是否在新視窗打開：'
		},
		'validLink':'只支援選中一個連結時生效',
		'httpPrompt':'您輸入的超連結中不包含http等協定名稱，預設將為您添加http://首碼'
	},
	'map':{
		'static':{
			lang_city:"城市",
			lang_address:"地址",
			city:{value:"北京"},
			lang_search:"搜索",
			lang_dynamicmap:"插入動態地圖"
		},
		cityMsg:"請選擇城市",
		errorMsg:"抱歉，找不到該位置！"
	},
	'searchreplace':{
		'static':{
			lang_tab_search:"查找",
			lang_tab_replace:"替換",
			lang_search1:"查找",
			lang_search2:"查找",
			lang_replace:"替換",
			lang_searchReg:'支持規則運算式，添加前後斜杠標示為規則運算式，例如“/運算式/”',
			lang_searchReg1:'支持規則運算式，添加前後斜杠標示為規則運算式，例如“/運算式/”',
			lang_case_sensitive1:"區分大小寫",
			lang_case_sensitive2:"區分大小寫",
			nextFindBtn:{value:"下一個"},
			preFindBtn:{value:"上一個"},
			nextReplaceBtn:{value:"下一個"},
			preReplaceBtn:{value:"上一個"},
			repalceBtn:{value:"替換"},
			repalceAllBtn:{value:"全部替換"}
		},
		getEnd:"已經搜索到文章末尾！",
		getStart:"已經搜索到文章頭部",
		countMsg:"總共替換了{#count}處！"
	},
	'snapscreen':{
		'static':{
			lang_showMsg:"截圖功能需要首先安裝UEditor截圖外掛程式！ ",
			lang_download:"點此下載",
			lang_step1:"第一步，下載UEditor截圖外掛程式並運行安裝。",
			lang_step2:"第二步，外掛程式安裝完成後即可使用，如不生效，請重啟流覽器後再試！"
		}
	},
	'spechars':{
		'static':{},
		tsfh:"特殊字元",
		lmsz:"羅馬字元",
		szfh:"數學字元",
		rwfh:"日文字元",
		xlzm:"希臘字母",
		ewzm:"俄文字元",
		pyzm:"拼音字母",
		yyyb:"英語音標",
		zyzf:"其他"
	},
	'edittable':{
		'static':{
			'lang_tableStyle':'表格樣式',
			'lang_insertCaption':'添加表格名稱行',
			'lang_insertTitle':'添加表格標題行',
			'lang_insertTitleCol':'添加表格標題列',
			'lang_orderbycontent':"使表格內容可排序",
			'lang_tableSize':'自動調整表格尺寸',
			'lang_autoSizeContent':'按表格文字自我調整',
			'lang_autoSizePage':'按頁面寬度自我調整',
			'lang_example':'示例',
			'lang_borderStyle':'表格邊框',
			'lang_color':'顏色:'
		},
		captionName:'表格名稱',
		titleName:'標題',
		cellsName:'內容',
		errorMsg:'有合併儲存格，不可排序'
	},
	'edittip':{
		'static':{
			lang_delRow:'刪除整行',
			lang_delCol:'刪除整列'
		}
	},
	'edittd':{
		'static':{
			lang_tdBkColor:'背景顏色:'
		}
	},
	'formula':{
		'static':{
		}
	},
	'wordimage':{
		'static':{
			lang_resave:"轉存步驟",
			uploadBtn:{src:"upload.png",alt:"上傳"},
			clipboard:{style:"background: url(copy.png) -153px -1px no-repeat;"},
			lang_step:"1、點擊頂部複製按鈕，將位址複製到剪貼板；2、點擊添加照片按鈕，在彈出的對話方塊中使用Ctrl+V粘貼位址；3、點擊打開後選擇圖片上傳流程。"
		},
		'fileType':"圖片",
		'flashError':"FLASH初始化失敗，請檢查FLASH外掛程式是否正確安裝！",
		'netError':"網路連接錯誤，請重試！",
		'copySuccess':"圖片位址已經複製！",
		'flashI18n':{} //留空默認中文
	},
	'autosave': {
		'saving':'保存中...',
		'success':'本地保存成功'
	}
};
