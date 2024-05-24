#leap year
'''
y=int(input('enter a year'))
if y%4==0:
    if y%100!=0:
        print(' leap year')
    else:
        if y%400!=0:
            print('not a leap year')
        else:
            print('leap year')
else:
    print('not a leap year ')
'''
#city
'''
city=input("Enter a city")
if city == "Delhi":
    print('Red fort')
elif city == 'Agra':
    print('Taj Mahal')
elif city == 'Jaipur':
    print('Jal Mahal')
else:
    print("inavalid entry")
'''
#last digit of a number is divisible by 3 or not
num=int(input("Enter a number "))
num%=10
if num%3==0:
    print('last digit is',num,'\n its divisible by 3')
else:
    print('last digit is',num,'\n its not divisible by 3')
