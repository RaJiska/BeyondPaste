<?php

function show_page()
{
	global $Paste;

	if ($Paste->isPosted())
	{
		if (!pastePublish($Paste))
		{
			?>
			<script>
				window.onload = function() {
					paste_publishDisplayAlert("alert-danger", <?php echo json_encode($Paste->getErrorStr(), JSON_HEX_TAG); ?>);
				};
			</script>
			<?php
		}
		else
		{
			?>
			<script>
				window.location.href = "/" + <?php echo json_encode($Paste->getPasteLink()); ?>; 
			</script>
			<?php
		}
	}

	?>
	<form action="/" method="POST" onsubmit="return paste_formIsValid()" >
		<div class="mt-3">
			<h2>New Paste</h2>
			<hr>
		</div>

		<div id="paste_alertid" class="alert" role="alert" hidden></div>
		<input type="hidden" name="paste_post"></input>

		<div class="row">
			<div class="col-sm-5">
				<label for="paste_title">Title</label>
				<div class="input-group">
					<input type="text" class="form-control" id="paste_title" name="paste_title" placeholder="Title" maxlength="50" autocomplete="off">
				</div>
			</div>
		</div>

		<div class="mt-2"></div>

		<div class="row">
			<div class="col-sm-2">
				<label for="paste_expiration">Expiration</label>
				<select name="paste_expiration" id="paste_expiration" class="form-control form-control-sm">
					<option value="1h">1 Hour</option>
					<option value="1d">1 Day</option>
					<option value="1w">1 Week</option>
					<option value="2w">2 Weeks</option>
					<option value="1m" selected >1 Month</option>
					<option value="6m">6 Months</option>
					<option value="1y">1 Year</option>
					<option value="never">Never</option>
				</select>
			</div>

			<div class="col-sm-3">
				<div class="form-check pt-4">
					<label class="form-check-label">
						<input type="checkbox" id="paste_autodestroy" name="paste_autodestroy"> 
							<img src="/resources/external/octicons/img/eye.svg" width=16 height=32>
							Automatically destroy after reading
						</input>
					</label>
				</div>
			</div>

			<div class="col-sm-4"></div>

			<div class="col-sm-3">
				<div class="form-group">
					<label for="paste_language">Syntax Highlighting</label>
					<select name="paste_language" id="paste_language" data-show-subtext="true" data-live-search="true" class="selectpicker form-control">
						<option value="text" default>Raw Text</option>
						<option value="abap">ABAP</option>
						<option value="actionscript">ActionScript</option>
						<option value="actionscript3">ActionScript 3</option>
						<option value="ada">Ada</option>
						<option value="aimms">AIMMS3</option>
						<option value="algol68">ALGOL 68</option>
						<option value="apache">Apache configuration</option>
						<option value="applescript">AppleScript</option>
						<option value="apt_sources">Apt sources</option>
						<option value="arm">ARM ASSEMBLER</option>
						<option value="asm">ASM</option>
						<option value="asp">ASP</option>
						<option value="asymptote">asymptote</option>
						<option value="autoconf">Autoconf</option>
						<option value="autohotkey">Autohotkey</option>
						<option value="autoit">AutoIt</option>
						<option value="avisynth">AviSynth</option>
						<option value="awk">awk</option>
						<option value="bascomavr">BASCOM AVR</option>
						<option value="bash">Bash</option>
						<option value="basic4gl">Basic4GL</option>
						<option value="batch">Windows Batch file</option>
						<option value="bf">Brainfuck</option>
						<option value="biblatex">BibTeX</option>
						<option value="bibtex">BibTeX</option>
						<option value="blitzbasic">BlitzBasic</option>
						<option value="bnf">bnf</option>
						<option value="boo">Boo</option>
						<option value="c">C</option>
						<option value="c_loadrunner">C (LoadRunner)</option>
						<option value="c_mac">C (Mac)</option>
						<option value="c_winapi">C (WinAPI)</option>
						<option value="caddcl">CAD DCL</option>
						<option value="cadlisp">CAD Lisp</option>
						<option value="ceylon">Ceylon</option>
						<option value="cfdg">CFDG</option>
						<option value="cfm">ColdFusion</option>
						<option value="chaiscript">ChaiScript</option>
						<option value="chapel">Chapel</option>
						<option value="cil">CIL</option>
						<option value="clojure">Clojure</option>
						<option value="cmake">CMake</option>
						<option value="cobol">COBOL</option>
						<option value="coffeescript">CoffeeScript</option>
						<option value="cpp">C++</option>
						<option value="cpp-qt" class="sublang">&nbsp;&nbsp;C++ (Qt)</option>
						<option value="cpp-winapi" class="sublang">&nbsp;&nbsp;C++ (WinAPI)</option>
						<option value="csharp">C#</option>
						<option value="css">CSS</option>
						<option value="cuesheet">Cuesheet</option>
						<option value="d">D</option>
						<option value="dart">Dart</option>
						<option value="dcl">DCL</option>
						<option value="dcpu16">DCPU-16 Assembly</option>
						<option value="dcs">DCS</option>
						<option value="delphi">Delphi</option>
						<option value="diff">Diff</option>
						<option value="div">DIV</option>
						<option value="dos">DOS</option>
						<option value="dot">dot</option>
						<option value="e">E</option>
						<option value="ecmascript">ECMAScript</option>
						<option value="eiffel">Eiffel</option>
						<option value="email">eMail (mbox)</option>
						<option value="epc">EPC</option>
						<option value="erlang">Erlang</option>
						<option value="euphoria">Euphoria</option>
						<option value="ezt">EZT</option>
						<option value="f1">Formula One</option>
						<option value="falcon">Falcon</option>
						<option value="fo">FO (abas-ERP)</option>
						<option value="fortran">Fortran</option>
						<option value="freebasic">FreeBasic</option>
						<option value="freeswitch">FreeSWITCH</option>
						<option value="fsharp">F#</option>
						<option value="gambas">GAMBAS</option>
						<option value="gdb">GDB</option>
						<option value="genero">genero</option>
						<option value="genie">Genie</option>
						<option value="gettext">GNU Gettext</option>
						<option value="glsl">glSlang</option>
						<option value="gml">GML</option>
						<option value="gnuplot">Gnuplot</option>
						<option value="go">Go</option>
						<option value="groovy">Groovy</option>
						<option value="gwbasic">GwBasic</option>
						<option value="haskell">Haskell</option>
						<option value="haxe">Haxe</option>
						<option value="hicest">HicEst</option>
						<option value="hq9plus">HQ9+</option>
						<option value="html4strict">HTML</option>
						<option value="html5">HTML5</option>
						<option value="icon">Icon</option>
						<option value="idl">Uno Idl</option>
						<option value="ini">INI</option>
						<option value="inno">Inno</option>
						<option value="intercal">INTERCAL</option>
						<option value="io">Io</option>
						<option value="ispfpanel">ISPF Panel</option>
						<option value="j">J</option>
						<option value="java">Java</option>
						<option value="java5">Java(TM) 2 Platform Standard Edition 5.0</option>
						<option value="javascript">Javascript</option>
						<option value="jcl">JCL</option>
						<option value="jquery">jQuery</option>
						<option value="julia">Julia</option>
						<option value="kixtart">KiXtart</option>
						<option value="klonec">KLone C</option>
						<option value="klonecpp">KLone C++</option>
						<option value="kotlin">Kotlin</option>
						<option value="latex">LaTeX</option>
						<option value="lb">Liberty BASIC</option>
						<option value="ldif">LDIF</option>
						<option value="lisp">Lisp</option>
						<option value="llvm">LLVM Intermediate Representation</option>
						<option value="locobasic">Locomotive Basic</option>
						<option value="logtalk">Logtalk</option>
						<option value="lolcode">LOLcode</option>
						<option value="lotusformulas">Lotus Notes @Formulas</option>
						<option value="lotusscript">LotusScript</option>
						<option value="lscript">LScript</option>
						<option value="lsl2">LSL2</option>
						<option value="lua">Lua</option>
						<option value="m68k">Motorola 68000 Assembler</option>
						<option value="magiksf">MagikSF</option>
						<option value="make">GNU make</option>
						<option value="mapbasic">MapBasic</option>
						<option value="mathematica">Mathematica</option>
						<option value="matlab">Matlab M</option>
						<option value="mercury">Mercury</option>
						<option value="metapost">MetaPost</option>
						<option value="mirc">mIRC Scripting</option>
						<option value="mk-61" class="sublang">&nbsp;&nbsp;МК-61/52</option>
						<option value="mmix">MMIX</option>
						<option value="modula2">Modula-2</option>
						<option value="modula3">Modula-3</option>
						<option value="mpasm">Microchip Assembler</option>
						<option value="mxml">MXML</option>
						<option value="mysql">MySQL</option>
						<option value="nagios">Nagios</option>
						<option value="netrexx">NetRexx</option>
						<option value="newlisp">newlisp</option>
						<option value="nginx">nginx</option>
						<option value="nimrod">Nimrod</option>
						<option value="nsis">NSIS</option>
						<option value="oberon2">Oberon-2</option>
						<option value="objc">Objective-C</option>
						<option value="objeck">Objeck Programming Language</option>
						<option value="ocaml">OCaml</option>
						<option value="ocaml-brief" class="sublang">&nbsp;&nbsp;OCaml (brief)</option>
						<option value="octave">GNU/Octave</option>
						<option value="oobas">OpenOffice.org Basic</option>
						<option value="oorexx">ooRexx</option>
						<option value="oracle11">Oracle 11 SQL</option>
						<option value="oracle8">Oracle 8 SQL</option>
						<option value="oxygene">Oxygene</option>
						<option value="oz">OZ</option>
						<option value="parasail">ParaSail</option>
						<option value="parigp">PARI/GP</option>
						<option value="pascal">Pascal</option>
						<option value="pcre">PCRE</option>
						<option value="per">per</option>
						<option value="perl">Perl</option>
						<option value="perl6">Perl 6</option>
						<option value="pf">OpenBSD Packet Filter</option>
						<option value="phix">Phix</option>
						<option value="php">PHP</option>
						<option value="php-brief" class="sublang">&nbsp;&nbsp;PHP (brief)</option>
						<option value="pic16">PIC16</option>
						<option value="pike">Pike</option>
						<option value="pixelbender">Pixel Bender 1.0</option>
						<option value="pli">PL/I</option>
						<option value="plsql">PL/SQL</option>
						<option value="postgresql">PostgreSQL</option>
						<option value="postscript">PostScript</option>
						<option value="povray">POVRAY</option>
						<option value="powerbuilder">PowerBuilder</option>
						<option value="powershell">PowerShell</option>
						<option value="proftpd">ProFTPd configuration</option>
						<option value="progress">Progress</option>
						<option value="prolog">Prolog</option>
						<option value="properties">PROPERTIES</option>
						<option value="providex">ProvideX</option>
						<option value="purebasic">PureBasic</option>
						<option value="pycon">Python (console mode)</option>
						<option value="pys60">Python for S60</option>
						<option value="python">Python</option>
						<option value="q">q/kdb+</option>
						<option value="qbasic">QBasic/QuickBASIC</option>
						<option value="qml">QML</option>
						<option value="racket">Racket</option>
						<option value="rails">Rails</option>
						<option value="rbs">RBScript</option>
						<option value="rebol">REBOL</option>
						<option value="reg">Microsoft Registry</option>
						<option value="rexx">rexx</option>
						<option value="robots">robots.txt</option>
						<option value="rpmspec">RPM Specification File</option>
						<option value="rsplus">R / S+</option>
						<option value="ruby">Ruby</option>
						<option value="rust">Rust</option>
						<option value="sas">SAS</option>
						<option value="sass">Sass</option>
						<option value="scala">Scala</option>
						<option value="scheme">Scheme</option>
						<option value="scilab">SciLab</option>
						<option value="scl">SCL</option>
						<option value="sdlbasic">sdlBasic</option>
						<option value="smalltalk">Smalltalk</option>
						<option value="smarty">Smarty</option>
						<option value="spark">SPARK</option>
						<option value="sparql">SPARQL</option>
						<option value="sql">SQL</option>
						<option value="standardml">StandardML</option>
						<option value="stonescript">StoneScript</option>
						<option value="swift">Swift</option>
						<option value="systemverilog">SystemVerilog</option>
						<option value="tcl">TCL</option>
						<option value="tclegg">TCLEGG</option>
						<option value="teraterm">Tera Term Macro</option>
						<option value="texgraph">TeXgraph</option>
						<option value="thinbasic">thinBasic</option>
						<option value="tsql">T-SQL</option>
						<option value="twig">Twig</option>
						<option value="typoscript">TypoScript</option>
						<option value="unicon">Unicon (Unified Extended Dialect of Icon)</option>
						<option value="upc">UPC</option>
						<option value="urbi">Urbi</option>
						<option value="uscript">Unreal Script</option>
						<option value="vala">Vala</option>
						<option value="vb">Visual Basic</option>
						<option value="vbnet">vb.net</option>
						<option value="vbscript">VBScript</option>
						<option value="vedit">Vedit macro language</option>
						<option value="verilog">Verilog</option>
						<option value="vhdl">VHDL</option>
						<option value="vim">Vim Script</option>
						<option value="visualfoxpro">Visual Fox Pro</option>
						<option value="visualprolog">Visual Prolog</option>
						<option value="whitespace">Whitespace</option>
						<option value="whois">Whois (RPSL format)</option>
						<option value="winbatch">Winbatch</option>
						<option value="xbasic">XBasic</option>
						<option value="xml">XML</option>
						<option value="xojo">Xojo</option>
						<option value="xorg_conf">Xorg configuration</option>
						<option value="xpp">X++</option>
						<option value="yaml">YAML</option>
						<option value="z80">ZiLOG Z80 Assembler</option>
						<option value="zxbasic">ZXBasic</option>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group" id="paste_content_formid">
					<label class="form-control-label" for="paste_contentid" id="paste_content_errorid" hidden></label>
    				<textarea id="paste_contentid" name="paste_content" class="form-control" maxlength="65000" rows="15"></textarea>
				</div>
			</div>
		</div>

		<h4>Access</h4>
		<hr>

		<div class="row-fluid">
			<div class="col-sm-5">

			<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input" type="radio" name="paste_access" value=0 checked>
						Free
					</input>
					<span class="badge badge-default">Default</span>
				</label>

				<br>

				<label class="form-check-label">
					<input class="form-check-input" type="radio" name="paste_access" value=1>
						Unlisted
					</input>
				</label>
			</div>
		</div>

		<div class="row-fluid">
			<hr>
			<button type="submit" class="btn btn-success">Create Paste</button>
		</div>
	</form>
	<?php
}

function pastePublish(&$Paste)
{
	if (!$Paste->isPostValid())
		return false;
	$Paste->loadFromPost();
	if (!$Paste->publish())
		return false;
	return true;
}