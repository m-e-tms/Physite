#!lua

if (#arg ~= 1) then
    print("error: incorrect number of arguments\nusage: ./navupall.lua <source_file_name>.html")
    os.exit(1)
end

local result = io.popen("find . -name \"*.html\"", "r")

for line in result:lines() do
    arg[2] = line
    dofile("navup.lua")
end

result:close()
