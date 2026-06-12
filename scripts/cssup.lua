#!lua

if (#arg ~= 2) then
    print("error: incorrect number of arguments\nusage: ./cssup.lua <source_file_name>.html <destination_file_name>.html")
    os.exit(1)
end

local css_pattern = "<link rel=\"stylesheet\" href=\".-\">"

local source_file = io.open(arg[1], "r")
local css_string = source_file:read("a"):match(css_pattern)--:gsub("[^\n]-\n", "  %0")
source_file:close()

css_string = "<link rel=\"stylesheet\" href=\"..\\..\\Physite\\scripts\\test.css\">"

local destination_file = io.open(arg[2], "r")
local content = destination_file:read("a")
destination_file:close()

destination_file = io.open(arg[2], "w")
destination_file:write(content:gsub(css_pattern, css_string))
destination_file:flush()
destination_file:close()
