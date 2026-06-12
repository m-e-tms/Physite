#!lua

if (#arg ~= 2) then
    print("error: incorrect number of arguments\nusage: ./navbar_update.lua <source_file_name>.html <destination_file_name>.html")
    os.exit(1)
end

local navbar_pattern = "<nav[^>]->.-</nav>"

local source_file = io.open(arg[1], "r")
local navbar_string = source_file:read("a"):match(navbar_pattern)--:gsub("[^\n]-\n", "  %0")
source_file:close()

local destination_file = io.open(arg[2], "r")
local content = destination_file:read("a")
destination_file:close()

destination_file = io.open(arg[2], "w")
destination_file:write(content:gsub(navbar_pattern, navbar_string))
destination_file:flush()
destination_file:close()
