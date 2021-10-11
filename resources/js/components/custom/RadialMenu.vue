<template>
  <div
    v-show="isOpened"
    class="radialMenu"
    :style="{ width: size + 'px', height: size + 'px' }"
  >
  <a href="http://localhost:8000/img/avt/1631362976_zbioAvt_909" download></a>
    <svg class="icons">
      <symbol id="return" viewBox="0 0 489.394 489.394">
        <path
          d="M375.789,92.867H166.864l17.507-42.795c3.724-9.132,1-19.574-6.691-25.744c-7.701-6.166-18.538-6.508-26.639-0.879L9.574,121.71c-6.197,4.304-9.795,11.457-9.563,18.995c0.231,7.533,4.261,14.446,10.71,18.359l147.925,89.823c8.417,5.108,19.18,4.093,26.481-2.499c7.312-6.591,9.427-17.312,5.219-26.202l-19.443-41.132h204.886c15.119,0,27.418,12.536,27.418,27.654v149.852c0,15.118-12.299,27.19-27.418,27.19h-226.74c-20.226,0-36.623,16.396-36.623,36.622v12.942c0,20.228,16.397,36.624,36.623,36.624h226.74c62.642,0,113.604-50.732,113.604-113.379V206.709C489.395,144.062,438.431,92.867,375.789,92.867z"
        ></path>
      </symbol>
      <symbol id="close" viewBox="0 0 41.756 41.756">
        <path
          d="M27.948,20.878L40.291,8.536c1.953-1.953,1.953-5.119,0-7.071c-1.951-1.952-5.119-1.952-7.07,0L20.878,13.809L8.535,1.465c-1.951-1.952-5.119-1.952-7.07,0c-1.953,1.953-1.953,5.119,0,7.071l12.342,12.342L1.465,33.22c-1.953,1.953-1.953,5.119,0,7.071C2.44,41.268,3.721,41.755,5,41.755c1.278,0,2.56-0.487,3.535-1.464l12.343-12.342l12.343,12.343c0.976,0.977,2.256,1.464,3.535,1.464s2.56-0.487,3.535-1.464c1.953-1.953,1.953-5.119,0-7.071L27.948,20.878z"
        ></path>
      </symbol>
    </svg>
    <svg
      class="menu"
      :class="{ inner: menu.inner, outer: menu.outer }"
      :width="size"
      :height="size"
      viewBox="-50 -50 100 100"
      v-for="(menu, index) in openedMenu"
      :id="'lvl' + menu.level"
      :key="index"
    >
      <g
        @click="$_sectorClick"
        @mouseover="$_sectorHover"
        :class="{
          sector: sector.id,
          dummy: !sector.id,
          selected: menu.selectedIndex == sector.itemIndex,
        }"
        v-for="(sector, index) in menu.sectors"
        :data-id="sector.id"
        :data-index="index"
        :transform="sector.transform"
        :data-item-index="sector.itemIndex"
        :key="index"
      >
        <path :d="sector.d"></path>
        <text
          v-if="sector.title"
          text-anchor="middle"
          font-size="38%"
          :x="sector.centerX"
          :y="sector.centerY"
          :transform="sector.textTransform"
        >
          {{ sector.title }}
        </text>
        <use
          v-if="sector.icon"
          :xlink:href="sector.icon"
          :x="sector.centerX"
          :y="sector.centerY"
          :transform="sector.useTransform"
          width="10"
          height="10"
          fill="white"
        ></use>
        <icon
          :name="sector.icon"
          v-if="sector.icon"
          :x="sector.centerX - 5.5"
          :y="sector.centerY - 10"
          width="12"
          height="12"
          color="#fff"
        ></icon>
      </g>
      <g class="center" @click="$_centerClick">
        <circle :r="menu.centerRadius"></circle>
        <use
          :xlink:href="menu.centerIcon"
          :transform="menu.centerTransform"
          :width="menu.centerSize"
          :height="menu.centerSize"
        ></use>
      </g>
    </svg>
  </div>
</template>
<script>
import UploadAvt from '../childs/UploadAvt.vue'
import BaseRequest from "../../cores/BaseRequest";
"use strict";

function Helper() {}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Helper.degToRad = (deg) => {
  return deg * (Math.PI / 180);
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Helper.getDegreePos = (angleDeg, length) => {
  return {
    x: Math.sin(Helper.degToRad(angleDeg)) * length,
    y: Math.cos(Helper.degToRad(angleDeg)) * length,
  };
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Helper.pointToString = (point) => {
  return Helper.numberToString(point.x) + " " + Helper.numberToString(point.y);
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Helper.numberToString = (n) => {
  if (Number.isInteger(n)) {
    return n.toString();
  } else if (n) {
    let r = (+n).toFixed(5);
    if (r.match(/\./)) {
      r = r.replace(/\.?0+$/, "");
    }
    return r;
  }
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Helper.resolveLoopIndex = (index, length) => {
  if (index < 0) {
    index = length + index;
  }
  if (index >= length) {
    index = index - length;
  }
  if (index < length) {
    return index;
  } else {
    return null;
  }
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Helper.waitForTransitionEnd = (node, propertyName) => {
  return new Promise(function (resolve) {
    function handler(event) {
      if (event.target == node && event.propertyName == propertyName) {
        node.removeEventListener("transitionend", handler);
        resolve();
      }
    }
    node.addEventListener("transitionend", handler);
  });
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Helper.nextTick = (fn) => {
  setTimeout(fn, 15);
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Helper.getIndexOffset = (items, sectorCount) => {
  if (items.length < sectorCount) {
    switch (items.length) {
      case 1:
        return -2;
      case 2:
        return -2;
      case 3:
        return -2;
      default:
        return -1;
    }
  } else {
    return -1;
  }
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Helper.calcScale = (sectorSpace, sectorCount, radius) => {
  let totalSpace = sectorSpace * sectorCount;
  let circleLength = Math.PI * 2 * radius;
  let radiusDelta = radius - (circleLength - totalSpace) / (Math.PI * 2);
  return (radius - radiusDelta) / radius;
};

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
export default {
  name: "RadialMenu",
  props: ["menuItems", "size", "closeOnClick","imgAvt"],
  data: function () {
    return {
      isOpened: false,
      openedMenu: [],
      imagesAvt: ["/img/avt/avt-default.png"],
      options: {
        title: false,
        movable: false,
        fullscreen: false,
      },
    };
  },
  components:{
    UploadAvt
  },
  created: function () {
    this.radius = 50;
    this.innerRadius = this.radius * 0.4;
    this.sectorSpace = this.radius * 0.06;
  },
  mounted: function () {
    document.addEventListener("keydown", this.$_keyDown.bind(this));
    document.addEventListener("wheel", this.$_mouseWheel.bind(this));
  },
  beforeDestroy: function () {
    document.removeEventListener("keydown", this.$_keyDown);
    document.removeEventListener("wheel", this.$_mouseWheel);
  },
  methods: {
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    open: function () {
      if (!this.isOpened) {
        this.isOpened = true;
        let layerInfo = this.$_createMenuLayer(this.menuItems, 0);
        this.openedMenu.push(layerInfo);
        Helper.nextTick(function () {
          layerInfo.inner = false;
        });
      }
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    close: function () {
      if (this.isOpened) {
        let currentMenu = this.$_getCurrentMenu();
        let node = document.getElementById("lvl" + currentMenu.level);
        Helper.waitForTransitionEnd(node, "visibility").then(() => {
          this.openedMenu = [];
          this.isOpened = false;
        });
        currentMenu.inner = true;
      }
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_mouseWheel: function (event) {
      if (this.isOpened) {
        let delta = -event.deltaY;
        if (delta > 0) {
          this.$_selectDelta(1);
        } else {
          this.$_selectDelta(-1);
        }
      }
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_keyDown: function (event) {
      if (this.isOpened) {
        switch (event.key) {
          case "Escape":
          case "Backspace":
            this.$_centerClick();
            event.preventDefault();
            break;
          case "Enter":
            this.$_menuClick(this.$_getSelectedItem());
            event.preventDefault();
            break;
          case "ArrowRight":
          case "ArrowUp":
            this.$_selectDelta(1);
            event.preventDefault();
            break;
          case "ArrowLeft":
          case "ArrowDown":
            this.$_selectDelta(-1);
            event.preventDefault();
            break;
        }
      }
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_createMenuLayer: function (items, level) {
      let info = {
        inner: true,
        outer: false,
        level: level,
        levelItems: items,
        sectors: [],
        selectedIndex: 0,
      };
      let sectorCount = (items.length, 4);
      let scale = Helper.calcScale(this.sectorSpace, sectorCount, this.radius);

      let angleStep = 360 / sectorCount;
      let angleShift = angleStep / 2 + 270;
      let indexOffset = Helper.getIndexOffset(items, sectorCount);

      for (let i = 0; i < sectorCount; ++i) {
        let startAngle = angleShift + angleStep * i;
        let endAngle = angleShift + angleStep * (i + 1);
        let itemIndex = Helper.resolveLoopIndex(
          sectorCount - i + indexOffset,
          sectorCount
        );
        let item;
        if (itemIndex >= 0 && itemIndex < items.length) {
          item = items[itemIndex];
        } else {
          item = null;
        }
        let sector = this.$_createSector(startAngle, endAngle, scale, item);
        sector.itemIndex = itemIndex;
        info.sectors.push(sector);
      }

      info.centerRadius = this.innerRadius - this.sectorSpace / 3;
      info.centerSize = level > 0 ? 8 : 7;
      info.centerIcon = level > 0 ? "#return" : "#close";
      info.centerTransform =
        "translate(-" +
        Helper.numberToString(info.centerSize / 2) +
        ",-" +
        Helper.numberToString(info.centerSize / 2) +
        ")";
      return info;
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_centerClick: function (event) {
      if (this.openedMenu.length > 1) {
        let childMenu = this.$_getCurrentMenu();
        let parentMenu = this.openedMenu[this.openedMenu.length - 2];
        let svgNode = document.getElementById("lvl" + childMenu.level);
        Helper.waitForTransitionEnd(svgNode, "visibility").then(() => {
          this.openedMenu.pop();
        });
        childMenu.inner = true;
        parentMenu.outer = false;
      } else {
        this.close();
      }
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_getCurrentMenu: function () {
      return this.openedMenu[this.openedMenu.length - 1];
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_sectorHover: function (event) {
      if (event.target.parentNode.classList.contains("sector")) {
        let dataset = event.target.parentNode.dataset;
        if (dataset.itemIndex !== undefined) {
          let currentMenu = this.$_getCurrentMenu();
          currentMenu.selectedIndex = parseInt(dataset.itemIndex);
        }
      }
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_getSelectedItem: function () {
      let currentMenu = this.$_getCurrentMenu();
      return currentMenu.levelItems[currentMenu.selectedIndex];
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_selectDelta: function (indexDelta) {
      let currentMenu = this.$_getCurrentMenu();
      let selectedIndex = currentMenu.selectedIndex + indexDelta;
      if (selectedIndex < 0) {
        selectedIndex = currentMenu.levelItems.length + selectedIndex;
      } else if (selectedIndex >= currentMenu.levelItems.length) {
        selectedIndex -= currentMenu.levelItems.length;
      }
      currentMenu.selectedIndex = selectedIndex;
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_menuClick: function (item) {
      if (item.id == "more") {
        this.$_openNestedMenu(item);
      } else {
        if (item.id == "viewAvt") {
          this.viewAvt();
        }
        if (item.id == "changeAvt") {
          this.changeAvt();
        }
        if (item.id == "save") {
          this.downloadImg(this.imgAvt);
        }
        this.$emit("clicked", item);
        if (this.closeOnClick) {
          this.close();
        }
      }
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_sectorClick: function (event) {
      if (event.target.parentNode.classList.contains("sector")) {
        let dataset = event.target.parentNode.dataset;
        let currentMenu = this.$_getCurrentMenu();
        if (dataset.itemIndex !== undefined) {
          let item = currentMenu.levelItems[dataset.itemIndex];
          // console.log('sector click:', item);
          this.$_menuClick(item);
        }
      }
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_openNestedMenu: function (item) {
      let parentMenu = this.$_getCurrentMenu();
      let newMenu = this.$_createMenuLayer(item.items, this.openedMenu.length);
      parentMenu.outer = true;
      this.openedMenu.push(newMenu);
      Helper.nextTick(function () {
        newMenu.inner = false;
      });
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_createSector: function (startAngleDeg, endAngleDeg, scale, item) {
      let info = {};
      let centerPoint = this.$_getSectorCenter(startAngleDeg, endAngleDeg);
      info.centerX = Helper.numberToString(centerPoint.x);
      info.centerY = Helper.numberToString(centerPoint.y);

      let translate = {
        x: Helper.numberToString((1 - scale) * centerPoint.x),
        y: Helper.numberToString((1 - scale) * centerPoint.y),
      };
      info.transform =
        "translate(" +
        translate.x +
        "," +
        translate.y +
        ") scale(" +
        scale +
        ")";
      info.d = this.$_getSectorPathCmd(startAngleDeg, endAngleDeg, scale);

      if (item) {
        info.className = "sector";
        info.id = item.id;

        if (item.title) {
          info.title = item.title;
          if (item.icon) {
            info.textTransform = "translate(0,8)";
          } else {
            info.textTransform = "translate(0,2)";
          }
        }

        if (item.icon) {
          info.icon = item.icon;
          if (item.title) {
            info.useTransform = "translate(-5,-8)";
          } else {
            info.useTransform = "translate(-5,-5)";
          }
        }
      }
      return info;
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_getSectorPathCmd: function (startAngleDeg, endAngleDeg, scale) {
      let initPoint = Helper.getDegreePos(startAngleDeg, this.radius);
      let path = "M" + Helper.pointToString(initPoint);

      let radiusAfterScale = this.radius * (1 / scale);
      path +=
        "A" +
        radiusAfterScale +
        " " +
        radiusAfterScale +
        " 0 0 0" +
        Helper.pointToString(Helper.getDegreePos(endAngleDeg, this.radius));
      path +=
        "L" +
        Helper.pointToString(
          Helper.getDegreePos(endAngleDeg, this.innerRadius)
        );

      let radiusDiff = this.radius - this.innerRadius;
      let radiusDelta = (radiusDiff - radiusDiff * scale) / 2;
      let innerRadius = (this.innerRadius + radiusDelta) * (1 / scale);
      path +=
        "A" +
        innerRadius +
        " " +
        innerRadius +
        " 0 0 1 " +
        Helper.pointToString(
          Helper.getDegreePos(startAngleDeg, this.innerRadius)
        );
      path += "Z";

      return path;
    },

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $_getSectorCenter: function (startAngleDeg, endAngleDeg) {
      return Helper.getDegreePos(
        (startAngleDeg + endAngleDeg) / 2,
        this.innerRadius + (this.radius - this.innerRadius) / 2
      );
    },
    viewAvt: function () {
     
      BaseRequest.get("useravts/" + this.$route.params.id)
        .then((response) => {
          if (response.data.length != 0) {
            this.imagesAvt = response.data;
          } else {
            this.imagesAvt = ["/img/avt/avt-default.png"];
          }
           this.$viewerApi({
            options: this.options,
            images: this.imagesAvt,
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
    changeAvt() {
      this.$root.$refs.UploadAvt.toggleShow();
    },
  downloadImg(url) {
      var link = document.createElement('a');
      link.href = url;
      link.download = Date.now()+'_avtarZbio.jpg';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
  },
};
</script>
<style scoped>
div.radialMenu {
  position: relative;
  margin: 10px;
  user-select: none;
  -moz-user-select: none;
}

div.radialMenu > svg.icons {
  display: none;
}

div.radialMenu > svg.menu {
  position: absolute;
  overflow: visible;
  transition: 0.2s;
  transition-timing-function: ease-out;
}

div.radialMenu > svg.menu.inner {
  transform: scale(0.66) rotate(-10deg);
  opacity: 0;
  visibility: hidden;
}

div.radialMenu > svg.menu.outer {
  transform: scale(1.5) rotate(10deg);
  opacity: 0;
  visibility: hidden;
}

div.radialMenu > svg.menu > g > path {
  fill: #00000080;
}

div.radialMenu > svg.menu > g.sector > path {
  cursor: pointer;
}

div.radialMenu > svg.menu > g.sector > text,
div.radialMenu > svg.menu > g.sector > use {
  cursor: pointer;
  fill: white;
}

div.radialMenu > svg.menu > g.sector.selected > path {
  fill: #32cd32d0 !important;
}

div.radialMenu > svg.menu > g.center:hover > circle {
  fill: #f9a602d0;
}

div.radialMenu > svg.menu > g.center > circle {
  cursor: pointer;
  fill: #00000080;
}

div.radialMenu > svg.menu > g.center > text,
div.radialMenu > svg.menu > g.center > use {
  cursor: pointer;
  fill: white;
}
</style>
